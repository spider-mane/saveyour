<?php

namespace Tests\Suites\Unit\Controller\Abstracts;

use Tests\Support\TestCase;
use WebTheory\Saveyour\Controller\Abstracts\AbstractField;

class AbstractFieldTest extends TestCase
{
    protected AbstractField $sut;

    protected string $dummyRequestVar;

    protected string $dummyInputValue;

    protected array $dummyRequestBody;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dummyRequestVar = $this->fake->slug;
        $this->dummyInputValue = $this->fake->sentence;
        $this->dummyRequestBody = [$this->dummyRequestVar => $this->dummyInputValue];

        $this->sut = $this->getMockForAbstractClass(AbstractField::class, [
            $this->dummyRequestVar,
            [],
            true,
        ]);
    }

    /**
     * @test
     */
    public function it_does_not_throw_exception_on_construction()
    {
        # Arrange
        $this->getMockForAbstractClass(AbstractField::class, [
            $this->dummyRequestVar,
            [],
            true,
        ]);

        # Expect
        $this->expectNotToPerformAssertions();
    }

    /**
     * @test
     */
    public function it_returns_provided_request_variable()
    {
        $this->assertEquals($this->dummyRequestVar, $this->sut->getRequestVar());
    }

    /**
     * @test
     */
    public function it_returns_provided_fields_to_await()
    {
        # Arrange
        $await = $this->dummyList(fn () => $this->unique->slug);

        # Act
        $sut = $this->getMockForAbstractClass(AbstractField::class, [
            $this->dummyRequestVar,
            $await,
        ]);

        # Assert
        $this->assertEquals($await, $sut->mustAwait());
    }

    /**
     * @test
     * @dataProvider processingEnabledDataProvider
     */
    public function it_returns_proper_processing_permission_status(bool $isEnabled)
    {
        # Arrange
        $sut = $this->getMockForAbstractClass(AbstractField::class, [
            $this->dummyRequestVar,
            [],
            $isEnabled,
        ]);

        # Act
        $result = $sut->isPermittedToProcess();

        # Assert
        $this->assertEquals($isEnabled, $result);
    }

    public function processingEnabledDataProvider(): array
    {
        return [
            'enabled' => [true],
            'disabled' => [false],
        ];
    }
}
