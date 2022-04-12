<?php

namespace WebTheory\Saveyour\Data;

use Psr\Http\Message\ServerRequestInterface;
use WebTheory\Saveyour\Contracts\Data\FieldDataManagerInterface;

class LazyManager implements FieldDataManagerInterface
{
    public function getCurrentData(ServerRequestInterface $request)
    {
        return '';
    }

    public function handleSubmittedData(ServerRequestInterface $request, $data): bool
    {
        return false;
    }
}
