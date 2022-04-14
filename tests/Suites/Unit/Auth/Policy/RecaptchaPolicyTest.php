<?php

namespace Tests\Suites\Unit\Auth\Policy;

use Psr\Http\Message\ServerRequestInterface;
use ReCaptcha\ReCaptcha;
use ReCaptcha\Response;
use Tests\Support\UnitTestCase;
use WebTheory\HttpPolicy\ServerRequestPolicyInterface;
use WebTheory\Saveyour\Auth\Policy\ReCaptchaPolicy;

class RecaptchaPolicyTest extends UnitTestCase
{
    protected ReCaptchaPolicy $sut;

    protected ReCaptcha $mockRecaptcha;

    protected Response $mockResponse;

    protected ServerRequestInterface $mockRequest;

    protected string $dummyRecaptchaParam;

    protected string $dummyReCaptchaResponse;

    protected array $dummyRequestBody;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dummyRecaptchaParam = $this->fake->slug;
        $this->dummyReCaptchaResponse = $this->fake->password;
        $this->dummyRequestBody = [
            $this->dummyRecaptchaParam => $this->dummyReCaptchaResponse,
        ];

        $this->mockRequest = $this->createMock(ServerRequestInterface::class);
        $this->mockRequest->method('getMethod')->willReturn('POST');
        $this->mockRequest->method('getParsedBody')->willReturn($this->dummyRequestBody);

        $this->mockRecaptcha = $this->createMock(ReCaptcha::class);
        $this->mockResponse = $this->createMock(Response::class);

        $this->sut = new ReCaptchaPolicy(
            $this->dummyRecaptchaParam,
            $this->mockRecaptcha
        );
    }

    /**
     * @test
     */
    public function it_is_instance_of_ServerRequestPolicyInterface()
    {
        $this->assertInstanceOf(ServerRequestPolicyInterface::class, $this->sut);
    }

    /**
     * @test
     * @dataProvider validationDataProvider
     */
    public function it_validates_according_to_reCaptcha_response(bool $status)
    {
        # Arrange
        $this->mockResponse->method('isSuccess')->willReturn($status);

        # Expect
        $this->mockRecaptcha->expects($this->once())
            ->method('verify')
            ->with($this->dummyReCaptchaResponse)
            ->willReturn($this->mockResponse);

        # Act
        $result = $this->sut->approvesRequest($this->mockRequest);

        # Assert
        $this->assertEquals($status, $result);
    }

    public function validationDataProvider(): array
    {
        return [
            'success' => [true],
            'failure' => [false],
        ];
    }
}
