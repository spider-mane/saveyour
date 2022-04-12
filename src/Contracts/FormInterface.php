<?php

namespace WebTheory\Saveyour\Contracts;

use Psr\Http\Message\ServerRequestInterface;
use Traversable;

interface FormInterface extends Traversable
{
    public function data(ServerRequestInterface $request): array;

    public function html(ServerRequestInterface $request): ?string;

    public function process(ServerRequestInterface $request): ProcessedFormReportInterface;
}
