<?php

namespace WebTheory\Saveyour\Report;

use WebTheory\Saveyour\Contracts\Report\FormProcessReportInterface;

class FormProcessReport implements FormProcessReportInterface
{
    /**
     * @var array<string,mixed>
     */
    protected array $results = [];

    /**
     * @param array<string,mixed> $results
     */
    public function __construct(array $results = [])
    {
        $this->results = $results;
    }

    public function results(): array
    {
        return $this->results;
    }

    public function resultFor(string $process)
    {
        return $this->results[$process] ?? null;
    }
}
