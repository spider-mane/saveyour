<?php

namespace Tests\Suites\Unit\Auth;

use Psr\Http\Message\ServerRequestInterface;
use Tests\Support\TestCase;
use WebTheory\HttpPolicy\ServerRequestPolicyInterface;
use WebTheory\Saveyour\Auth\FormShield;

class FormShieldTest extends TestCase
{
    protected FormShield $sut;

    protected ServerRequestPolicyInterface $mockPolicy;

    protected ServerRequestInterface $mockRequest;

    protected string $dummyRequestVar;

    protected array $mockPolicyArray;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dummyRequestVar = $this->fake->slug;
        $this->mockRequest = $this->createMock(ServerRequestInterface::class);
        $this->mockPolicy = $this->createMock(ServerRequestPolicyInterface::class);

        $this->mockPolicyArray = [
            $this->dummyRequestVar => $this->mockPolicy,
        ];

        $this->sut = new FormShield($this->mockPolicyArray);
    }

    /**
     * @test
     * @dataProvider authenticationStatusProvider
     */
    public function it_generates_an_accurate_report(bool $status)
    {
        # Arrange
        $violations = $status ? [] : [$this->dummyRequestVar];

        $this->mockPolicy->method('approvesRequest')->willReturn($status);

        # Act
        $result = $this->sut->analyzeRequest($this->mockRequest);

        # Act
        $this->assertSame($status, $result->verificationStatus());
        $this->assertSame($violations, $result->ruleViolations());
    }

    /**
     * @test
     * @dataProvider authenticationStatusProvider
     */
    public function it_accurately_reflects_verification_status(bool $status)
    {
        # Arrange
        $this->mockPolicy->method('approvesRequest')->willReturn($status);

        # Act
        $result = $this->sut->approvesRequest($this->mockRequest);

        # Assert
        $this->assertSame($status, $result);
    }

    public function authenticationStatusProvider(): array
    {
        return [
            'success' => [true],
            'failure' => [false],
        ];
    }
}
