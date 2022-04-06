<?php

namespace WebTheory\Saveyour\Contracts;

interface ProcessedFormReportBuilderInterface extends ProcessedFormReportInterface
{
    public function withShieldReport(FormShieldReportInterface $report): ProcessedFormReportBuilderInterface;

    public function withFieldReport(string $field, ProcessedFieldReportInterface $report): ProcessedFormReportBuilderInterface;

    public function withProcessReport(string $process, FormProcessReportInterface $report): ProcessedFormReportBuilderInterface;

    public function build(): ProcessedFormReportInterface;
}
