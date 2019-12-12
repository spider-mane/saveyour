<?php

namespace WebTheory\Saveyour\Contracts;

use Psr\Http\Message\ServerRequestInterface;

interface FormValidatorInterface
{
    /**
     *
     */
    public function isValid(ServerRequestInterface $request): bool;
}
