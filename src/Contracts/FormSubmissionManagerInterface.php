<?php

namespace WebTheory\Saveyour\Contracts;

use Psr\Http\Message\ServerRequestInterface;

interface FormSubmissionManagerInterface
{
    /**
     * @param ServerRequestInterface $request
     *
     * @return FormProcessingCacheInterface
     */
    public function process(ServerRequestInterface $request): FormProcessingCacheInterface;

    public function validate(ServerRequestInterface $request): bool;

    public function validated(ServerRequestInterface $request): array;
}
