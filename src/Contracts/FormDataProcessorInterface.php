<?php

namespace WebTheory\Saveyour\Contracts;

use Psr\Http\Message\ServerRequestInterface;

interface FormDataProcessorInterface
{
    /**
     * @param ServerRequestInterface $request
     * @param FieldOperationCacheInterface[] $results
     */
    public function process(ServerRequestInterface $request, array $results): ?FormDataProcessingCacheInterface;

    /**
     * @return string[]
     */
    public function getFields(): array;
}
