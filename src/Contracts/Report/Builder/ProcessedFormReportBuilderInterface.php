<?php

namespace WebTheory\Saveyour\Contracts\Report\Builder;

use WebTheory\Saveyour\Contracts\Report\FormProcessReportInterface;
use WebTheory\Saveyour\Contracts\Report\FormShieldReportInterface;
use WebTheory\Saveyour\Contracts\Report\ProcessedFormReportInterface;
use WebTheory\Saveyour\Contracts\Report\ProcessedInputReportInterface;

interface ProcessedFormReportBuilderInterface
{
    public function withShieldReport(FormShieldReportInterface $report): ProcessedFormReportBuilderInterface;

    public function withInputReport(string $input, ProcessedInputReportInterface $report): ProcessedFormReportBuilderInterface;

    public function withProcessReport(string $process, FormProcessReportInterface $report): ProcessedFormReportBuilderInterface;

    public function build(): ProcessedFormReportInterface;
}
