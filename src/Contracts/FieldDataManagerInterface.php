<?php

namespace WebTheory\Saveyour\Contracts;

use Psr\Http\Message\ServerRequestInterface;

interface FieldDataManagerInterface
{
    public function getCurrentData(ServerRequestInterface $request);

    public function handleSubmittedData(ServerRequestInterface $request, $data): bool;
}
