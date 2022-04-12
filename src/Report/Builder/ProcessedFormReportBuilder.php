<?php

namespace WebTheory\Saveyour\Report\Builder;

use WebTheory\Saveyour\Contracts\FormProcessReportInterface;
use WebTheory\Saveyour\Contracts\FormShieldReportInterface;
use WebTheory\Saveyour\Contracts\ProcessedFormReportBuilderInterface;
use WebTheory\Saveyour\Contracts\ProcessedFormReportInterface;
use WebTheory\Saveyour\Contracts\ProcessedInputReportInterface;
use WebTheory\Saveyour\Report\ProcessedFormReport;

class ProcessedFormReportBuilder implements ProcessedFormReportBuilderInterface
{
    protected array $inputReports = [];

    protected array $processReports = [];

    protected FormShieldReportInterface $shieldReport;

    public function __construct(ProcessedFormReportInterface $previous = null)
    {
        if ($previous) {
            $this->inputReports = $previous->inputReports();
            $this->processReports = $previous->processReports();
            $this->shieldReport = $previous->shieldReport();
        }
    }

    public function withShieldReport(FormShieldReportInterface $report): ProcessedFormReportBuilderInterface
    {
        $this->shieldReport = $report;

        return $this;
    }

    public function withInputReport(string $input, ProcessedInputReportInterface $report): ProcessedFormReportBuilderInterface
    {
        $this->inputReports[$input] = $report;

        return $this;
    }

    public function withProcessReport(string $process, FormProcessReportInterface $report): ProcessedFormReportBuilderInterface
    {
        $this->processReports[$process] = $report;

        return $this;
    }

    public function build(): ProcessedFormReportInterface
    {
        return new ProcessedFormReport(
            $this->shieldReport,
            $this->inputReports,
            $this->processReports
        );
    }
}
