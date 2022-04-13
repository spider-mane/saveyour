<?php

namespace Tests\Suites\Unit\Validation;

use Respect\Validation\Validatable;
use Tests\Support\TestCase;
use WebTheory\Saveyour\Validation\RespectValidator;

class RespectValidatorTest extends TestCase
{
    protected RespectValidator $sut;

    protected Validatable $mockValidatable;

    protected string $dummyRuleName;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dummyRuleName = $this->fake->slug;

        $this->mockValidatable = $this->createMock(Validatable::class);
        $this->mockValidatable->method('getName')->willReturn($this->dummyRuleName);

        $this->sut = new RespectValidator($this->mockValidatable);
    }

    /**
     * @test
     * @dataProvider validationDataProvider
     */
    public function it_provides_an_accurate_validation_report(bool $isValid)
    {
        # Arrange
        $value = $this->fake->title;
        $violations = $isValid ? [] : [$this->dummyRuleName];

        # Expect
        $this->mockValidatable->expects($this->once())
            ->method('validate')
            ->with($value)
            ->willReturn($isValid);

        # Act
        $report = $this->sut->inspect($value);

        # Assert
        $this->assertEquals($isValid, $report->validationStatus());
        $this->assertEquals($violations, $report->ruleViolations());
    }

    /**
     * @test
     * @dataProvider validationDataProvider
     */
    public function it_accurately_reports_validation_status(bool $isValid)
    {
        # Arrange
        $this->mockValidatable->method('validate')->willReturn($isValid);

        # Assert
        $this->assertEquals($isValid, $this->sut->validate($this->fake->title));
    }

    public function validationDataProvider(): array
    {
        return [
            'passed' => [true],
            'failed' => [false],
        ];
    }
}
