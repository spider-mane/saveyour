<?php

namespace WebTheory\Saveyour\Concerns;

use Psr\Http\Message\ServerRequestInterface;

trait ShantHaveCurrentData
{
    public function getCurrentData(ServerRequestInterface $request)
    {
        return '';
    }
}
