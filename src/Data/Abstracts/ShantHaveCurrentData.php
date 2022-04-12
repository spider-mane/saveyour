<?php

namespace WebTheory\Saveyour\Data\Abstracts;

use Psr\Http\Message\ServerRequestInterface;

trait ShantHaveCurrentData
{
    public function getCurrentData(ServerRequestInterface $request)
    {
        return '';
    }
}
