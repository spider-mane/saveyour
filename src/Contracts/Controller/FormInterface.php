<?php

namespace WebTheory\Saveyour\Contracts\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Traversable;
use WebTheory\Saveyour\Contracts\Report\ProcessedFormReportInterface;

interface FormInterface extends Traversable
{
    public function data(ServerRequestInterface $request): array;

    public function html(ServerRequestInterface $request): ?string;

    public function process(ServerRequestInterface $request): ProcessedFormReportInterface;
}
