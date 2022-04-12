<?php

namespace WebTheory\Saveyour\Report;

use WebTheory\Saveyour\Contracts\FormProcessReportInterface;

class FormProcessReport implements FormProcessReportInterface
{
    protected array $results = [];

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
