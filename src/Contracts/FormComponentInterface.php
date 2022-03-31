<?php

namespace WebTheory\Saveyour\Contracts;

use Psr\Http\Message\ServerRequestInterface;

interface FormComponentInterface
{
    public function render(ServerRequestInterface $request): string;
}
