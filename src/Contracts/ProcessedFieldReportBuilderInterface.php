<?php

namespace WebTheory\Saveyour\Contracts;

interface ProcessedFieldReportBuilderInterface
{
    public function withSanitizedInputValue($value): ProcessedFieldReportBuilderInterface;

    public function withUpdateAttempted(bool $result): ProcessedFieldReportBuilderInterface;

    public function withUpdateSuccessful(bool $result): ProcessedFieldReportBuilderInterface;

    public function build(): ProcessedFieldReportInterface;
}
