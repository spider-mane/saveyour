<?php

namespace WebTheory\Saveyour\Auth\Policy;

use Psr\Http\Message\ServerRequestInterface;
use ReCaptcha\ReCaptcha;
use ReCaptcha\Response;
use WebTheory\HttpPolicy\ServerRequestPolicyInterface;
use WebTheory\Saveyour\Http\Request;

class ReCaptchaPolicy implements ServerRequestPolicyInterface
{
    protected string $response;

    protected ReCaptcha $reCaptcha;

    public function __construct(string $response, ReCaptcha $reCaptcha)
    {
        $this->response = $response;
        $this->reCaptcha = $reCaptcha;
    }

    public function approvesRequest(ServerRequestInterface $request): bool
    {
        return $this->getApiResponse($request)->isSuccess();
    }

    protected function getApiResponse(ServerRequestInterface $request): Response
    {
        return $this->reCaptcha->verify($this->getGeneratedResponse($request));
    }

    protected function getGeneratedResponse(ServerRequestInterface $request): string
    {
        return Request::var($request, $this->response);
    }
}
