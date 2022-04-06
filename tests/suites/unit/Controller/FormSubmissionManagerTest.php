<?php

namespace Tests\Suites\Unit\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Tests\Support\TestCase;
use WebTheory\Saveyour\Contracts\FieldOperationCacheInterface;
use WebTheory\Saveyour\Contracts\FormDataProcessorInterface;
use WebTheory\Saveyour\Contracts\FormFieldControllerInterface;
use WebTheory\Saveyour\Contracts\FormShieldInterface;
use WebTheory\Saveyour\Contracts\FormShieldReportInterface;
use WebTheory\Saveyour\Contracts\FormSubmissionManagerInterface;
use WebTheory\Saveyour\Contracts\ProcessedFormReportInterface;
use WebTheory\Saveyour\Controllers\FormDataProcessingCache;
use WebTheory\Saveyour\Controllers\FormSubmissionManager;

class FormSubmissionManagerTest extends TestCase
{
    protected FormSubmissionManager $manager;

    protected FormShieldInterface $stubShield;

    protected FormFieldControllerInterface $stubField;

    protected FormDataProcessorInterface $stubProcessor;

    protected ServerRequestInterface $stubRequest;

    protected FieldOperationCacheInterface $stubFieldReport;

    protected FormShieldReportInterface $stubShieldReport;

    protected FormDataProcessingCache $stubProcessReport;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stubShieldReport = $this->createStub(FormShieldReportInterface::class);
        $this->stubFieldReport = $this->createStub(FieldOperationCacheInterface::class);
        $this->stubProcessReport = $this->createStub(FormDataProcessingCache::class);

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

        /** @var ProcessedFormReportInterface $report */
        $report = $this->manager->process($this->stubRequest);

        $this->assertInstanceOf(ProcessedFormReportInterface::class, $report);
        $this->assertSame($this->stubShieldReport, $report->shieldReport());
        $this->assertContains($this->stubFieldReport, $report->fieldReports());
        $this->assertContains($this->stubProcessReport, $report->processReports());
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
        $this->assertEmpty($report->fieldReports());
        $this->assertEmpty($report->processReports());
    }

    /**
     * @test
     * @dataProvider verificationDataProvider
=     */
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
            $manager->process($this->stubRequest)->fieldReports()
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

        $this->assertEmpty($this->manager->process($request)->fieldReports());
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
     */
    public function it_passes_all_field_reports_to_processor_with_null_getFields_return_value()
    {
        $processor = $this->createMock(FormDataProcessorInterface::class);
        $processor->method('process')->willReturn($this->stubProcessReport);
        $processor->method('getFields')->willReturn(null);

        $this->stubField->method('validate')->willReturn(true);

        $manager = new FormSubmissionManager([$this->stubField], [$processor]);
        $expected = [$this->stubField->getRequestVar() => $this->stubFieldReport];

        // assert
        $processor->expects($this->once())
            ->method('process')
            ->with($this->stubRequest, $expected);

        $manager->process($this->stubRequest);
    }

    /**
     * @test
     */
    public function it_passes_specified_field_reports_to_processor_when_they_are_specified()
    {
        $processor = $this->createMock(FormDataProcessorInterface::class);
        $processor->method('process')->willReturn($this->stubProcessReport);
        $processor->method('getFields')->willReturn([$this->stubField->getRequestVar()]);

        $manager = new FormSubmissionManager([$this->stubField], [$processor]);
        $expected = [$this->stubField->getRequestVar() => $this->stubFieldReport];

        // assert
        $processor->expects($this->once())
            ->method('process')
            ->with($this->stubRequest, $expected);

        $manager->process($this->stubRequest);
    }

    /**
     * @test
     */
    public function it_does_not_pass_unspecified_field_reports_to_processor()
    {
        $processor = $this->createMock(FormDataProcessorInterface::class);
        $processor->method('process')->willReturn($this->stubProcessReport);
        $processor->method('getFields')->willReturn([]);

        $manager = new FormSubmissionManager([$this->stubField], [$processor]);
        $expected = [];

        // assert
        $processor->expects($this->once())
            ->method('process')
            ->with($this->stubRequest, $expected);

        $manager->process($this->stubRequest);
    }
}
