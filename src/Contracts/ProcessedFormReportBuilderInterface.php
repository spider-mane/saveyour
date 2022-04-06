<?php

namespace WebTheory\Saveyour\Contracts;

interface ProcessedFormReportBuilderInterface extends ProcessedFormReportInterface
{
    public function withShieldReport(FormShieldReportInterface $report): ProcessedFormReportBuilderInterface;

    public function withFieldReport(string $field, FieldOperationCacheInterface $report): ProcessedFormReportBuilderInterface;

    public function withProcessReport(string $process, FormDataProcessingCacheInterface $report): ProcessedFormReportBuilderInterface;

    public function build(): ProcessedFormReportInterface;
}
