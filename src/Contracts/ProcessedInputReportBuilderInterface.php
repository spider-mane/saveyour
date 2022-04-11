<?php

namespace WebTheory\Saveyour\Contracts;

interface ProcessedInputReportBuilderInterface extends ValidationReportBuilderInterface, ProcessedFieldReportBuilderInterface
{
    public function withRequestVarPresent(bool $result): ProcessedFieldReportBuilderInterface;

    public function withRawInputValue(string $value): ProcessedInputReportBuilderInterface;

    public function build(): ProcessedInputReportInterface;
}
