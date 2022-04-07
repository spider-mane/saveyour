<?php

namespace Tests\Suites\Unit\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Tests\Support\TestCase;
use WebTheory\Saveyour\Contracts\FormDataProcessorInterface;
use WebTheory\Saveyour\Contracts\FormFieldControllerInterface;
use WebTheory\Saveyour\Contracts\FormProcessReportInterface;
use WebTheory\Saveyour\Contracts\FormShieldInterface;
use WebTheory\Saveyour\Contracts\FormShieldReportInterface;
use WebTheory\Saveyour\Contracts\FormSubmissionManagerInterface;
use WebTheory\Saveyour\Contracts\ProcessedFieldReportInterface;
use WebTheory\Saveyour\Contracts\ProcessedFormReportInterface;
use WebTheory\Saveyour\Contracts\ProcessedInputReportInterface;
use WebTheory\Saveyour\Controllers\FormSubmissionManager;

class FormSubmissionManagerTest extends TestCase
{
    protected FormSubmissionManager $manager;

    protected FormShieldInterface $stubShield;

    protected FormFieldControllerInterface $stubField;

    protected FormDataProcessorInterface $stubProcessor;

    protected ServerRequestInterface $stubRequest;

    protected ProcessedFieldReportInterface $stubFieldReport;

    protected FormShieldReportInterface $stubShieldReport;

    protected FormProcessReportInterface $stubProcessReport;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stubShieldReport = $this->createStub(FormShieldReportInterface::class);
        $this->stubFieldReport = $this->createStub(ProcessedFieldReportInterface::class);
        $this->stubProcessReport = $this->createStub(FormProcessReportInterface::class);

        $this->stubShield = $this->createStub(FormShieldInterface::class);
        $this->stubShield->method('analyzeRequest')->willReturn($this->stubShieldReport);

        $this->stubField = $this->createStub(FormFieldControllerInterface::class);
        $this->stubField->method('process')->willReturn($this->stubFieldReport);
        $this->stubField->method('getRequestVar')->willReturn($this->faker->slug);

        $this->stubProcessor = $this->createStub(FormDataProcessorInterface::class);
        $this->stubProcessor->method('process')->willReturn($this->stubProcessReport);

        $this->stubRequest = $this->createStub(ServerRequestInterface::class);
        $this->stubRequest->method('getParsedBody')->willReturn([
            $this->stubField->getRequestVar() => $this->faker->text,
        ]);

        $this->manager = new FormSubmissionManager(
            [$this->stubField],
            [$this->stubProcessor],
            $this->stubShield
        );
    }

    /**
     * @test
     */
    public function it_is_instance_of_FormSubmissionManagerInterface()
    {
        $this->assertInstanceOf(FormSubmissionManagerInterface::class, $this->manager);
    }

    /**
     * @test
     */
    public function it_processes_form_and_generates_report()
    {
        $this->stubShieldReport->method('verificationStatus')->willReturn(true);

        $this->stubFieldReport->method('sanitizedInputValue')->willReturn($this->faker->dateTime);
        $this->stubFieldReport->method('updateAttempted')->willReturn(true);
        $this->stubFieldReport->method('updateSuccessful')->willReturn(true);
        $this->stubFieldReport->method('validationStatus')->willReturn(true);
        $this->stubFieldReport->method('ruleViolations')->willReturn([]);

        /** @var ProcessedFormReportInterface $report */
        $report = $this->manager->process($this->stubRequest);

        $this->assertExpectedProcessedFormReport($report);
    }

    protected function assertExpectedProcessedFormReport(ProcessedFormReportInterface $report)
    {
        $inputReports = $report->inputReports();
        $input = $this->stubField->getRequestVar();
        $inputReport = $inputReports[$input];

        $this->assertInstanceOf(ProcessedFormReportInterface::class, $report);
        $this->assertSame($this->stubShieldReport, $report->shieldReport());
        $this->assertContains($this->stubProcessReport, $report->processReports());

        $this->assertInputReportHasExpectedValues($inputReport);
    }

    protected function assertInputReportHasExpectedValues(ProcessedInputReportInterface $report)
    {
        $params = $this->stubRequest->getParsedBody();
        $rawInput = $params[$this->stubField->getRequestVar()];
        $requestVarPresent = true;
        $fieldReport = $this->stubFieldReport;

        $comparisons = [
            'sanitizedInputValue',
            'updateAttempted',
            'updateSuccessful',
            'validationStatus',
            'ruleViolations',
        ];

        $this->assertSame($rawInput, $report->rawInputValue());
        $this->assertSame($requestVarPresent, $report->requestVarPresent());

        foreach ($comparisons as $comparison) {
            $this->assertSame($fieldReport->{$comparison}(), $report->{$comparison}());
        }
    }

    /**
     * @test
     */
    public function it_halts_processing_if_shield_rejects_request()
    {
        $this->stubShieldReport->method('verificationStatus')->willReturn(false);

        /** @var ProcessedFormReportInterface $report */
        $report = $this->manager->process($this->stubRequest);

        $this->assertInstanceOf(ProcessedFormReportInterface::class, $report);
        $this->assertSame($this->stubShieldReport, $report->shieldReport());
        $this->assertEmpty($report->inputReports());
        $this->assertEmpty($report->processReports());
    }

    /**
     * @test
     * @dataProvider verificationDataProvider
     */
    public function it_provides_the_verification_status(bool $valid)
    {
        $this->stubShield->method('approvesRequest')->willReturn($valid);

        $status = $this->manager->verify($this->stubRequest);

        if ($valid) {
            $this->assertTrue($status);
        } else {
            $this->assertFalse($status);
        }
    }

    public function verificationDataProvider(): array
    {
        return [
            'passed verification' => [true],
            'failed verification' => [false],
        ];
    }

    /**
     * @test
     * @dataProvider validatedDataProvider
     */
    public function it_returns_an_array_of_validated_inputs(bool $valid)
    {
        $this->stubField->method('validate')->willReturn($valid);

        $all = $this->stubRequest->getParsedBody();
        $validated = $this->manager->validated($this->stubRequest);

        if ($valid) {
            $this->assertEquals($all, $validated);
        } else {
            $this->assertNotEquals($all, $validated);
        }
    }

    public function validatedDataProvider(): array
    {
        return [
            'passed validation' => [true],
            'failed validation' => [false],
        ];
    }

    /**
     * @test
     * @dataProvider validateDataProvider
     */
    public function it_provides_complete_validation_status(bool $valid)
    {
        $this->stubField->method('validate')->willReturn($valid);

        $status = $this->manager->validate($this->stubRequest);

        if ($valid) {
            $this->assertTrue($status);
        } else {
            $this->assertFalse($status);
        }
    }

    public function validateDataProvider(): array
    {
        return [
            'passes validation' => [true],
            'fails validation' => [false],
        ];
    }

    /**
     * @test
     */
    public function it_provides_an_array_of_processed_fields()
    {
        $processed = $this->faker->dateTime;

        $this->stubField->method('validate')->willReturn(true);
        $this->stubField->method('getUpdatedValue')->willReturn($processed);

        $this->assertEquals(
            [$this->stubField->getRequestVar() => $processed],
            $this->manager->processed($this->stubRequest)
        );
    }

    /**
     * @test
     */
    public function it_processes_fields_specified_in_mustAwait_first()
    {
        // arrange
        $names = [
            $this->faker->word,
            $this->faker->word,
            $this->faker->word,
        ];

        $field1 = $this->createStub(FormFieldControllerInterface::class);
        $field2 = $this->createStub(FormFieldControllerInterface::class);
        $field3 = $this->createStub(FormFieldControllerInterface::class);

        $fields = [$field1, $field2, $field3];
        $await = $names[1];

        $manager = new FormSubmissionManager($fields);

        $field1->method('mustAwait')->willReturn([$await]);

        foreach ($fields as $field) {
            $field->method('process')->willReturn($this->stubFieldReport);

            $field->method('getRequestVar')->willReturn(current($names));
            next($names);
        }
        reset($names);

        // act
        $processed = array_keys(
            $manager->process($this->stubRequest)->inputReports()
        );

        // assert
        $this->assertEquals($await, $processed[0]);
    }

    /**
     * @test
     */
    public function it_only_processes_fields_if_they_are_present_in_request()
    {
        $request = $this->createStub(ServerRequestInterface::class);
        $request->method('getParsedBody')->willReturn([]);

        $this->assertEmpty($this->manager->process($request)->inputReports());
    }

    /**
     * @test
     */
    public function it_only_validates_fields_if_they_are_present_in_request()
    {
        $request = $this->createStub(ServerRequestInterface::class);
        $request->method('getParsedBody')->willReturn([]);

        $this->stubField->method('validate')->willReturn(true);

        $this->assertEmpty($this->manager->validated($request));
    }

    /**
     * @test
     * @dataProvider processDataProvider
     */
    public function it_passes_appropriate_field_reports_to_processor(string $requestVar, ?array $fields)
    {
        // arrange
        $requestVarPresent = true;
        $rawInputValue = $this->faker->word;

        $field = $this->createStub(FormFieldControllerInterface::class);
        $field->method('getRequestVar')->willReturn($requestVar);
        $field->method('process')->willReturn($this->stubFieldReport);
        $field->method('validate')->willReturn(true);

        $request = $this->createStub(ServerRequestInterface::class);
        $request->method('getMethod')->willReturn('POST');
        $request->method('getParsedBody')->willReturn([
            $field->getRequestVar() => $rawInputValue,
        ]);

        $processor = $this->createMock(FormDataProcessorInterface::class);
        $processor->method('process')->willReturn($this->stubProcessReport);
        $processor->method('getFields')->willReturn($fields);

        $manager = new FormSubmissionManager([$field], [$processor]);

        $this->stubFieldReport->method('sanitizedInputValue')->willReturn($this->faker->dateTime);
        $this->stubFieldReport->method('updateAttempted')->willReturn(true);
        $this->stubFieldReport->method('updateSuccessful')->willReturn(true);
        $this->stubFieldReport->method('validationStatus')->willReturn(true);
        $this->stubFieldReport->method('ruleViolations')->willReturn([]);

        if (isset($fields) && !in_array($requestVar, $fields)) {
            $expected = [$fields[0] => null];
        } else {
            $expected = $this->callback(function ($reports) use ($requestVar, $requestVarPresent, $rawInputValue) {
                $report = $reports[$requestVar] ?? null;

                return $report
                    && $report->requestVarPresent() === $requestVarPresent
                    && $report->rawInputValue() === $rawInputValue
                    && $report->sanitizedInputValue() === $this->stubFieldReport->sanitizedInputValue()
                    && $report->updateAttempted() === $this->stubFieldReport->updateAttempted()
                    && $report->updateSuccessful() === $this->stubFieldReport->updateSuccessful()
                    && $report->validationStatus() === $this->stubFieldReport->validationStatus()
                    && $report->ruleViolations() === $this->stubFieldReport->ruleViolations();
            });
        }

        // expect
        $processor->expects($this->once())
            ->method('process')
            ->with($request, $expected);

        // act
        $manager->process($request);
    }

    public function processDataProvider(): array
    {
        $faker = $this->createFaker();
        $var = $faker->slug;

        return [
            'null (default all)' => [$var, null],
            'specified - present' => [$var, [$var]],
            'specified - not present' => [$var, [$faker->slug]],
        ];
    }
}
