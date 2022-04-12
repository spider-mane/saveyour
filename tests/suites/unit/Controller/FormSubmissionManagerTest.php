<?php

namespace Tests\Suites\Unit\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Tests\Support\TestCase;
use WebTheory\Saveyour\Contracts\Auth\FormShieldInterface;
use WebTheory\Saveyour\Contracts\Controller\FormFieldControllerInterface;
use WebTheory\Saveyour\Contracts\Controller\FormSubmissionManagerInterface;
use WebTheory\Saveyour\Contracts\Processor\FormDataProcessorInterface;
use WebTheory\Saveyour\Contracts\Report\FormProcessReportInterface;
use WebTheory\Saveyour\Contracts\Report\FormShieldReportInterface;
use WebTheory\Saveyour\Contracts\Report\ProcessedFieldReportInterface;
use WebTheory\Saveyour\Contracts\Report\ProcessedFormReportInterface;
use WebTheory\Saveyour\Contracts\Report\ProcessedInputReportInterface;
use WebTheory\Saveyour\Contracts\Report\ValidationReportInterface;
use WebTheory\Saveyour\Controller\FormSubmissionManager;

class FormSubmissionManagerTest extends TestCase
{
    protected FormSubmissionManager $sut;

    protected FormShieldInterface $mockShield;

    protected FormFieldControllerInterface $mockField;

    protected FormDataProcessorInterface $mockProcessor;

    protected ServerRequestInterface $mockRequest;

    protected ValidationReportInterface $mockValidationReport;

    protected ProcessedFieldReportInterface $mockFieldReport;

    protected FormShieldReportInterface $mockShieldReport;

    protected FormProcessReportInterface $mockProcessReport;

    protected string $dummyRequestVar;

    protected string $dummyInputValue;

    protected array $dummyRequestBody;

    protected function setUp(): void
    {
        parent::setUp();

        $requestVar = $this->fake->slug;
        $inputValue = $this->fake->word;
        $requestBody = [$requestVar => $inputValue];

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getParsedBody')->willReturn($requestBody);

        $shieldReport = $this->createMock(FormShieldReportInterface::class);
        // $shieldReport->method('verificationStatus')->willReturn(true);

        $validationReport = $this->createMock(ValidationReportInterface::class);
        $validationReport->method('validationStatus')->willReturn(true);
        $validationReport->method('ruleViolations')->willReturn([]);

        $fieldReport = $this->createMock(ProcessedFieldReportInterface::class);
        // $fieldReport->method('validationStatus')->willReturn(true);
        // $fieldReport->method('ruleViolations')->willReturn([]);

        $processReport = $this->createMock(FormProcessReportInterface::class);

        $shield = $this->createMock(FormShieldInterface::class);
        $shield->method('analyzeRequest')->willReturn($shieldReport);

        $field = $this->createMock(FormFieldControllerInterface::class);
        $field->method('inspect')->willReturn($validationReport);
        $field->method('validate')->willReturn(true);
        $field->method('process')->willReturn($fieldReport);
        $field->method('getRequestVar')->willReturn($requestVar);
        $field->method('isPermittedToProcess')->willReturn(true);

        $processor = $this->createMock(FormDataProcessorInterface::class);
        $processor->method('process')->willReturn($processReport);

        $this->dummyRequestVar = $requestVar;
        $this->dummyInputValue = $inputValue;
        $this->dummyRequestBody = $requestBody;

        $this->mockRequest = $request;

        $this->mockValidationReport = $validationReport;
        $this->mockFieldReport = $fieldReport;
        $this->mockShieldReport = $shieldReport;
        $this->mockProcessReport = $processReport;

        $this->mockShield = $shield;
        $this->mockField = $field;
        $this->mockProcessor = $processor;

        $this->sut = new FormSubmissionManager(
            [$this->mockField],
            [$this->mockProcessor],
            $this->mockShield
        );
    }

    /**
     * @test
     */
    public function it_is_instance_of_FormSubmissionManagerInterface()
    {
        $this->assertInstanceOf(FormSubmissionManagerInterface::class, $this->sut);
    }

    /**
     * @test
     */
    public function it_processes_form_and_generates_report()
    {
        $this->mockShieldReport->method('verificationStatus')->willReturn(true);

        $this->mockFieldReport->method('sanitizedInputValue')->willReturn($this->fake->dateTime);
        $this->mockFieldReport->method('updateAttempted')->willReturn(true);
        $this->mockFieldReport->method('updateSuccessful')->willReturn(true);
        // $this->mockFieldReport->method('validationStatus')->willReturn(true);
        // $this->mockFieldReport->method('ruleViolations')->willReturn([]);

        /** @var ProcessedFormReportInterface $report */
        $report = $this->sut->process($this->mockRequest);

        $this->assertExpectedProcessedFormReport($report);
    }

    protected function assertExpectedProcessedFormReport(ProcessedFormReportInterface $report)
    {
        $inputReports = $report->inputReports();
        $input = $this->mockField->getRequestVar();
        $inputReport = $inputReports[$input];

        $this->assertInstanceOf(ProcessedFormReportInterface::class, $report);
        $this->assertSame($this->mockShieldReport, $report->shieldReport());
        $this->assertContains($this->mockProcessReport, $report->processReports());

        $this->assertInputReportHasExpectedValues($inputReport);
    }

    protected function assertInputReportHasExpectedValues(ProcessedInputReportInterface $report)
    {
        $params = $this->mockRequest->getParsedBody();
        $rawInput = $params[$this->mockField->getRequestVar()];
        $requestVarPresent = true;

        $validationComparisons = [
            'validationStatus',
            'ruleViolations',
        ];

        $processComparisons = [
            'sanitizedInputValue',
            'updateAttempted',
            'updateSuccessful',
        ];

        $this->assertSame($rawInput, $report->rawInputValue());
        $this->assertSame($requestVarPresent, $report->requestVarPresent());

        foreach ($validationComparisons as $comparison) {
            $this->assertSame(
                $this->mockValidationReport->$comparison(),
                $report->$comparison()
            );
        }

        foreach ($processComparisons as $comparison) {
            $this->assertSame(
                $this->mockFieldReport->{$comparison}(),
                $report->{$comparison}()
            );
        }
    }

    /**
     * @test
     */
    public function it_halts_processing_if_shield_rejects_request()
    {
        $this->mockShieldReport->method('verificationStatus')->willReturn(false);

        /** @var ProcessedFormReportInterface $report */
        $report = $this->sut->process($this->mockRequest);

        $this->assertInstanceOf(ProcessedFormReportInterface::class, $report);
        $this->assertSame($this->mockShieldReport, $report->shieldReport());
        $this->assertEmpty($report->inputReports());
        $this->assertEmpty($report->processReports());
    }

    /**
     * @test
     * @dataProvider verificationDataProvider
     */
    public function it_provides_the_verification_status(bool $valid)
    {
        $this->mockShield->method('approvesRequest')->willReturn($valid);

        $status = $this->sut->verify($this->mockRequest);

        if ($valid) {
            $this->assertTrue($status);
        } else {
            $this->assertFalse($status);
        }
    }

    public function verificationDataProvider(): array
    {
        return [
            'successful verification' => [true],
            'unsuccessful verification' => [false],
        ];
    }

    /**
     * @test
     * @dataProvider validationDataProvider
     */
    public function it_returns_an_array_of_validated_inputs(bool $isValid)
    {
        $field = $this->createMock(FormFieldControllerInterface::class);
        $field->method('getRequestVar')->willReturn($this->dummyRequestVar);

        $sut = new FormSubmissionManager([$field]);
        $field->method('validate')->willReturn($isValid);

        $all = $this->mockRequest->getParsedBody();
        $validated = $sut->validated($this->mockRequest);

        if ($isValid) {
            $this->assertEquals($all, $validated);
        } else {
            $this->assertNotEquals($all, $validated);
        }
    }

    /**
     * @test
     * @dataProvider validationDataProvider
     */
    public function it_provides_complete_validation_status(bool $isValid)
    {
        $field = $this->createMock(FormFieldControllerInterface::class);
        $field->method('getRequestVar')->willReturn($this->dummyRequestVar);

        $sut = new FormSubmissionManager([$field]);
        $field->method('validate')->willReturn($isValid);

        $status = $sut->validate($this->mockRequest);

        if ($isValid) {
            $this->assertTrue($status);
        } else {
            $this->assertFalse($status);
        }
    }

    /**
     * @test
     * @dataProvider validationDataProvider
     */
    public function it_processes_fields_only_if_input_is_valid(bool $isValid, array $violations)
    {
        # Arrange
        $validationReport = $this->createMock(ValidationReportInterface::class);
        $validationReport->method('validationStatus')->willReturn($isValid);
        $validationReport->method('ruleViolations')->willReturn($violations);

        $field = $this->createMock(FormFieldControllerInterface::class);
        $field->method('getRequestVar')->willReturn($this->dummyRequestVar);
        $field->method('isPermittedToProcess')->willReturn(true);
        $field->method('inspect')->willReturn($validationReport);
        $field->method('validate')->willReturn($isValid);

        $sut = new FormSubmissionManager([$field]);

        # Expect
        $field->expects($isValid ? $this->atLeastOnce() : $this->never())
            ->method('process');

        # Act
        $sut->process($this->mockRequest);
    }

    public function validationDataProvider(): array
    {
        $fake = $this->createFaker();

        return [
            'successful validation' => [true, []],
            'unsuccessful validation' => [false, [$fake->slug]],
        ];
    }

    /**
     * @test
     */
    public function it_provides_an_array_of_processed_fields()
    {
        $processed = $this->fake->dateTime;

        $this->mockField->method('validate')->willReturn(true);
        $this->mockField->method('getUpdatedValue')->willReturn($processed);

        $this->assertEquals(
            [$this->mockField->getRequestVar() => $processed],
            $this->sut->processed($this->mockRequest)
        );
    }

    /**
     * @test
     */
    public function it_processes_fields_specified_in_mustAwait_first()
    {
        // Arrange
        $names = [
            $this->fake->word,
            $this->fake->word,
            $this->fake->word,
        ];

        $field1 = $this->createStub(FormFieldControllerInterface::class);
        $field2 = $this->createStub(FormFieldControllerInterface::class);
        $field3 = $this->createStub(FormFieldControllerInterface::class);

        $fields = [$field1, $field2, $field3];
        $await = $names[1];

        $sut = new FormSubmissionManager($fields);

        $field1->method('mustAwait')->willReturn([$await]);

        foreach ($fields as $field) {
            $field->method('process')->willReturn($this->mockFieldReport);

            $field->method('getRequestVar')->willReturn(current($names));
            next($names);
        }
        reset($names);

        // Act
        $processed = array_keys(
            $sut->process($this->mockRequest)->inputReports()
        );

        // Assert
        $this->assertEquals($await, $processed[0]);
    }

    /**
     * @test
     */
    public function it_only_processes_fields_if_they_are_present_in_request()
    {
        $request = $this->createStub(ServerRequestInterface::class);
        $request->method('getParsedBody')->willReturn([]);

        $this->assertEmpty($this->sut->process($request)->inputReports());
    }

    /**
     * @test
     */
    public function it_only_validates_fields_if_they_are_present_in_request()
    {
        $request = $this->createStub(ServerRequestInterface::class);
        $request->method('getParsedBody')->willReturn([]);

        $this->mockField->method('validate')->willReturn(true);

        $this->assertEmpty($this->sut->validated($request));
    }

    /**
     * @test
     */
    public function it_passes_same_request_to_fields()
    {
        $this->mockShieldReport->method('verificationStatus')->willReturn(true);

        $this->mockField->expects($this->atLeastOnce())
            ->method('process')
            ->with($this->mockRequest);

        $this->sut->process($this->mockRequest);
    }

    /**
     * @test
     */
    public function it_passes_same_request_to_processors()
    {
        $this->mockShieldReport->method('verificationStatus')->willReturn(true);
        $this->mockProcessor->method('getFields')->willReturn([$this->mockField->getRequestVar()]);

        $this->mockProcessor->expects($this->atLeastOnce())
            ->method('process')
            ->with($this->mockRequest, $this->anything());

        $this->sut->process($this->mockRequest);
    }

    /**
     * @test
     */
    public function it_calls_FormFieldControllerInterface_instance_process_method_only_once()
    {
        $this->mockShieldReport->method('verificationStatus')->willReturn(true);

        $this->mockField->expects($this->once())->method('process');

        $this->sut->process($this->mockRequest);
    }

    /**
     * @test
     */
    public function it_calls_FormProcessorInterface_instance_process_method_only_once()
    {
        $this->mockShieldReport->method('verificationStatus')->willReturn(true);
        $this->mockProcessor->method('getFields')->willReturn([$this->mockField->getRequestVar()]);

        $this->mockProcessor->expects($this->once())->method('process');

        $this->sut->process($this->mockRequest);
    }

    /**
     * @test
     * @dataProvider processDataProvider
     */
    public function it_passes_appropriate_field_reports_to_processor(string $requestVar, ?array $fields)
    {
        // Arrange
        $requestVarPresent = true;
        $rawInputValue = $this->fake->word;

        $field = $this->createStub(FormFieldControllerInterface::class);
        $field->method('getRequestVar')->willReturn($requestVar);
        $field->method('isPermittedToProcess')->willReturn(true);
        $field->method('process')->willReturn($this->mockFieldReport);
        $field->method('validate')->willReturn(true);
        $field->method('inspect')->willReturn($this->mockValidationReport);

        $request = $this->createStub(ServerRequestInterface::class);
        $request->method('getMethod')->willReturn('POST');
        $request->method('getParsedBody')->willReturn([
            $field->getRequestVar() => $rawInputValue,
        ]);

        $processor = $this->createMock(FormDataProcessorInterface::class);
        $processor->method('process')->willReturn($this->mockProcessReport);
        $processor->method('getFields')->willReturn($fields);

        $sut = new FormSubmissionManager([$field], [$processor]);

        $this->mockFieldReport->method('sanitizedInputValue')->willReturn($this->fake->dateTime);
        $this->mockFieldReport->method('updateAttempted')->willReturn(true);
        $this->mockFieldReport->method('updateSuccessful')->willReturn(true);

        if (isset($fields) && !in_array($requestVar, $fields)) { // specified field not present
            $expected = [$fields[0] => null];
        } else {
            $expected = $this->callback(function ($reports) use ($requestVar, $requestVarPresent, $rawInputValue) {
                $report = $reports[$requestVar] ?? null;

                return $report
                    && $report instanceof ProcessedInputReportInterface
                    && $report->requestVarPresent() === $requestVarPresent
                    && $report->rawInputValue() === $rawInputValue
                    && $report->sanitizedInputValue() === $this->mockFieldReport->sanitizedInputValue()
                    && $report->updateAttempted() === $this->mockFieldReport->updateAttempted()
                    && $report->updateSuccessful() === $this->mockFieldReport->updateSuccessful()
                    && $report->validationStatus() === $this->mockValidationReport->validationStatus()
                    && $report->ruleViolations() === $this->mockValidationReport->ruleViolations();
            });
        }

        // Expect
        $processor->expects($this->once())
            ->method('process')
            ->with($this->anything(), $expected);

        // Act
        $sut->process($request);
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

    /**
     * @test
     * @dataProvider fieldProcessPermissionDataProvider
     */
    public function it_allows_field_process_operation_to_run_only_if_field_is_permitted_to_process(bool $canProcess)
    {
        # Arrange
        $field = $this->createStub(FormFieldControllerInterface::class);
        $field->method('getRequestVar')->willReturn($this->dummyRequestVar);
        $field->method('process')->willReturn($this->mockFieldReport);
        $field->method('inspect')->willReturn($this->mockValidationReport);

        $sut = new FormSubmissionManager([$field]);
        $field->method('isPermittedToProcess')->willReturn($canProcess);

        # Expect
        $field->expects($canProcess ? $this->atLeastOnce() : $this->never())
            ->method('process');

        # Act
        $result = $sut->process($this->mockRequest);

        # Assert
        // $this->assertEquals()
    }

    public function fieldProcessPermissionDataProvider(): array
    {
        return [
            'permitted' => [true],
            'not permitted' => [false],
        ];
    }
}
