<?php

namespace WebTheory\Saveyour\Contracts;

use Psr\Http\Message\ServerRequestInterface;

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
