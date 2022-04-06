<?php

namespace WebTheory\Saveyour\Controllers;

use WebTheory\Saveyour\Contracts\FieldOperationCacheInterface;
use WebTheory\Saveyour\Contracts\FormDataProcessingCacheInterface;
use WebTheory\Saveyour\Contracts\FormShieldReportInterface;
use WebTheory\Saveyour\Contracts\ProcessedFormReportBuilderInterface;
use WebTheory\Saveyour\Contracts\ProcessedFormReportInterface;

class ProcessedFormReportBuilder extends ProcessedFormReport implements ProcessedFormReportBuilderInterface
{
    public function __construct(ProcessedFormReportInterface $previous = null)
    {
        if ($previous) {
            $this->fieldReports = $previous->fieldReports();
            $this->processReports = $previous->processReports();
            $this->shieldReport = $previous->shieldReport();
        }
    }

    public function withShieldReport(FormShieldReportInterface $report): ProcessedFormReportBuilderInterface
    {
        $this->shieldReport = $report;

        return $this;
    }

    public function withFieldReport(string $field, FieldOperationCacheInterface $report): ProcessedFormReportBuilderInterface
    {
        $this->fieldReports[$field] = $report;

        return $this;
    }

    public function withProcessReport(string $process, FormDataProcessingCacheInterface $report): ProcessedFormReportBuilderInterface
    {
        $this->processReports[$process] = $report;

        return $this;
    }

    public function build(): ProcessedFormReportInterface
    {
        return new ProcessedFormReport(
            $this->shieldReport,
            $this->fieldReports,
            $this->processReports
        );
    }
}
