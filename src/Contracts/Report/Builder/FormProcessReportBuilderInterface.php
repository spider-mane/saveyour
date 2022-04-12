<?php

namespace WebTheory\Saveyour\Contracts\Report\Builder;

use WebTheory\Saveyour\Contracts\Report\FormProcessReportInterface;

interface FormProcessReportBuilderInterface
{
    public function withProcessResult(string $process, $result): FormProcessReportBuilderInterface;

    public function build(): FormProcessReportInterface;
}
