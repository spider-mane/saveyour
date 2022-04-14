<?php

namespace Tests\Suites\Unit\Http;

use Psr\Http\Message\ServerRequestInterface;
use Tests\Support\UnitTestCase;
use WebTheory\Saveyour\Http\Request;

class RequestTest extends UnitTestCase
{
    protected ServerRequestInterface $mockRequest;

    protected string $dummyParam;

    protected string $dummyInputValue;

    protected array $dummyRequestBody;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dummyParam = $this->fake->slug;
        $this->dummyInputValue = $this->fake->sentence;
        $this->dummyRequestBody = [$this->dummyParam => $this->dummyInputValue];

        $this->mockRequest = $this->createMock(ServerRequestInterface::class);
    }

    /**
     * @test
     * @dataProvider requestVariableDataProvider
     */
    public function it_can_find_request_variable(string $method, string $operation)
    {
        # Arrange
        $this->mockRequest->method('getMethod')->willReturn($method);

        # Expect
        $this->mockRequest->expects($this->once())
            ->method($operation)
            ->willReturn($this->dummyRequestBody);

        # Act
        $result = Request::var($this->mockRequest, $this->dummyParam);

        # Assert
        $this->assertEquals($this->dummyInputValue, $result);
    }

    /**
     * @test
     * @dataProvider requestVariableDataProvider
     */
    public function it_can_verify_request_variable_exists(string $method, string $operation)
    {
        # Arrange
        $this->mockRequest->method('getMethod')->willReturn($method);

        # Expect
        $this->mockRequest->expects($this->once())
            ->method($operation)
            ->willReturn($this->dummyRequestBody);

        # Act
        $result = Request::has($this->mockRequest, $this->dummyParam);

        # Assert
        $this->assertTrue($result);
    }

    public function requestVariableDataProvider(): array
    {
        return [
            'GET' => ['GET', 'getQueryParams'],
            'POST' => ['POST', 'getParsedBody'],
            'PUT' => ['PUT', 'getParsedBody'],
            'PATCH' => ['PATCH', 'getParsedBody'],
            'DELETE' => ['DELETE', 'getParsedBody'],
        ];
    }

    /**
     * @test
     */
    public function it_can_find_attribute()
    {
        # Arrange
        $this->mockRequest->method('getMethod')->willReturn('POST');

        # Expect
        $this->mockRequest->expects($this->once())
            ->method('getAttribute')
            ->willReturn($this->dummyInputValue);

        # Act
        $result = Request::attr($this->mockRequest, $this->dummyParam);

        # Assert
        $this->assertEquals($this->dummyInputValue, $result);
    }

    /**
     * @test
     */
    public function it_can_verify_attribute_exists()
    {
        # Arrange
        $this->mockRequest->method('getAttributes')->willReturn($this->dummyRequestBody);

        # Act
        $result = Request::hasAttr($this->mockRequest, $this->dummyParam);

        # Assert
        $this->assertTrue($result);
    }
}
