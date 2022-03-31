<?php

namespace WebTheory\Saveyour\Controllers;

use Psr\Http\Message\ResponseInterface;
use WebTheory\Saveyour\Contracts\FormDataProcessingCacheInterface;

class FormDataProcessingCache implements FormDataProcessingCacheInterface
{
    /**
     * @var ResponseInterface[]
     */
    protected $requestResponses;

    /**
     * @var array
     */
    protected $processingResults = [];

    public function getResultOf(string $process)
    {
        return $this->processingResults[$process];
    }

    public function withResult(string $process, $result)
    {
        $this->processingResults[$process] = $result;

        return $this;
    }

    public function getRequestResponse(?string $requestId = 'default'): ?ResponseInterface
    {
        return $this->requestResponses[$requestId] ?? null;
    }

    public function getRequestsResponses(): ?array
    {
        return $this->requestResponses;
    }

    public function withRequestResponse(ResponseInterface $response, string $requestId = 'default')
    {
        $this->requestResponses[$requestId] = $response;

        return $this;
    }
}
