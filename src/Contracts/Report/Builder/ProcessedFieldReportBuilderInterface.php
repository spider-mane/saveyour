<?php

namespace WebTheory\Saveyour\Contracts\Report\Builder;

use WebTheory\Saveyour\Contracts\Report\ProcessedFieldReportInterface;

interface ProcessedFieldReportBuilderInterface
{
    public function withSanitizedInputValue($value): ProcessedFieldReportBuilderInterface;

    public function withUpdateAttempted(bool $result): ProcessedFieldReportBuilderInterface;

    public function withUpdateSuccessful(bool $result): ProcessedFieldReportBuilderInterface;

    public function build(): ProcessedFieldReportInterface;
}
