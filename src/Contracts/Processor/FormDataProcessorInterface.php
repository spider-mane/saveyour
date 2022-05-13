<?php

namespace WebTheory\Saveyour\Contracts\Processor;

use Psr\Http\Message\ServerRequestInterface;
use WebTheory\Saveyour\Contracts\Report\FormProcessReportInterface;
use WebTheory\Saveyour\Contracts\Report\ProcessedFieldReportInterface;

interface FormDataProcessorInterface
{
    /**
     * @param ServerRequestInterface $request
     * @param array<string,ProcessedFieldReportInterface> $results
     */
    public function process(ServerRequestInterface $request, array $results): ?FormProcessReportInterface;

    /**
     * @return null|array<int,string>
     */
    public function getFields(): ?array;

    public function getName(): string;
}
