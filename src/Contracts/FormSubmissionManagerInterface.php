<?php

namespace WebTheory\Saveyour\Contracts;

use Psr\Http\Message\ServerRequestInterface;

interface FormSubmissionManagerInterface
{
    public function process(ServerRequestInterface $request): FormProcessingCacheInterface;

    public function verify(ServerRequestInterface $request): bool;

    public function validate(ServerRequestInterface $request): bool;

    public function validated(ServerRequestInterface $request): array;
}
