<?php

namespace Tests\Suites\Unit\Controller;

use Tests\Support\UnitTestCase;
use WebTheory\Saveyour\Contracts\Formatting\InputFormatterInterface;
use WebTheory\Saveyour\Contracts\Report\ValidationReportInterface;
use WebTheory\Saveyour\Contracts\Validation\ValidatorInterface;
use WebTheory\Saveyour\Controller\InputPurifier;

class InputPurifierTest extends UnitTestCase
{
    protected InputPurifier $sut;

    protected ValidatorInterface $mockValidator;

    protected InputFormatterInterface $mockInputFormatter;

    protected ValidationReportInterface $mockValidationReport;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockValidationReport = $this->createMock(ValidationReportInterface::class);

        $this->mockValidator = $this->createMock(ValidatorInterface::class);
        $this->mockValidator->method('inspect')->willReturn($this->mockValidationReport);

        $this->mockInputFormatter = $this->createMock(InputFormatterInterface::class);

        $this->sut = new InputPurifier($this->mockValidator, $this->mockInputFormatter);
    }

    /**
     * @test
     */
    public function it_returns_filtered_input_on_successful_validation()
    {
        # Arrange
        $input = $this->fake->word;
        $sanitized = $this->fake->rgbColor;

        $this->mockValidationReport->method('validationStatus')->willReturn(true);
        $this->mockValidationReport->method('ruleViolations')->willReturn([]);

        # Expect
        $this->mockValidator->expects($this->once())
            ->method('inspect')
            ->with($input)
            ->willReturn($this->mockValidationReport);

        $this->mockInputFormatter->expects($this->once())
            ->method('formatInput')
            ->with($input)
            ->willReturn($sanitized);

        # Act
        $result = $this->sut->handleInput($input);

        # Assert
        $this->assertEquals($sanitized, $result);
    }

    /**
     * @test
     */
    public function it_returns_null_on_unsuccessful_validation()
    {
        # Arrange
        $this->mockValidationReport->method('validationStatus')->willReturn(false);
        $this->mockValidationReport->method('ruleViolations')->willReturn(
            $this->dummyList(fn () => $this->fake->slug)
        );

        $this->mockValidator->method('validate')->willReturn(false);

        # Assert
        $this->assertNull($this->sut->handleInput($this->fake->word));
    }

    /**
     * @test
     */
    public function it_retrieves_rule_violations_on_unsuccessful_validation()
    {
        # Arrange
        $input = $this->fake->word;
        $violations = $this->dummyList(fn () => $this->unique->slug);

        $this->mockValidationReport->method('validationStatus')->willReturn(false);

        # Expect
        $this->mockValidationReport->expects($this->once())
            ->method('ruleViolations')
            ->willReturn($violations);

        # Act
        $this->sut->handleInput($input);
    }
}
