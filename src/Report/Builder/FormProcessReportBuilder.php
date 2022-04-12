<?php

namespace WebTheory\Saveyour\Report\Builder;

use WebTheory\Saveyour\Contracts\Report\Builder\FormProcessReportBuilderInterface;
use WebTheory\Saveyour\Contracts\Report\FormProcessReportInterface;
use WebTheory\Saveyour\Report\FormProcessReport;

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
