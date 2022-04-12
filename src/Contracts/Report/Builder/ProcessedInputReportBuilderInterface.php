<?php

namespace WebTheory\Saveyour\Contracts\Report\Builder;

use WebTheory\Saveyour\Contracts\Report\ProcessedInputReportInterface;

interface ProcessedInputReportBuilderInterface extends ValidationReportBuilderInterface, ProcessedFieldReportBuilderInterface
{
    public function withRequestVarPresent(bool $result): ProcessedFieldReportBuilderInterface;

    public function withRawInputValue(string $value): ProcessedInputReportBuilderInterface;

    public function build(): ProcessedInputReportInterface;
}
