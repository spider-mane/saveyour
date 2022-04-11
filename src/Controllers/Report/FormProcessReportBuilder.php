<?php

namespace WebTheory\Saveyour\Controllers\Report;

use WebTheory\Saveyour\Contracts\FormProcessReportBuilderInterface;
use WebTheory\Saveyour\Contracts\FormProcessReportInterface;
use WebTheory\Saveyour\Controllers\FormProcessReport;

class FormProcessReportBuilder implements FormProcessReportBuilderInterface
{
    protected $results;

    public function __construct(FormProcessReportInterface $previous = null)
    {
        if ($previous) {
            $this->results = $previous->results();
        }
    }

    public function withProcessResult(string $process, $result): FormProcessReportBuilderInterface
    {
        $this->results[$process] = $result;

        return $this;
    }

    public function build(): FormProcessReportInterface
    {
        return new FormProcessReport($this->results);
    }
}
