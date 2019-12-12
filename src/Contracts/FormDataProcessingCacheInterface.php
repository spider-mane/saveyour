<?php

namespace WebTheory\Saveyour\Contracts;

use Psr\Http\Message\ResponseInterface;

interface FormDataProcessingCacheInterface
{
    /**
     * @return mixed
     */
    public function getResultOf(string $process);

    /**
     * @return ResponseInterface|null
     */
    public function getRequestResponse(?string $requestId): ?ResponseInterface;

    /**
     * @return ResponseInterface[]|null
     */
    public function getRequestsResponses(): ?array;
}
