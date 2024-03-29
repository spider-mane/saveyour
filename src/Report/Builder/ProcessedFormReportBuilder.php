<?php

namespace WebTheory\Saveyour\Report\Builder;

use WebTheory\Saveyour\Contracts\Report\Builder\ProcessedFormReportBuilderInterface;
use WebTheory\Saveyour\Contracts\Report\FormProcessReportInterface;
use WebTheory\Saveyour\Contracts\Report\FormShieldReportInterface;
use WebTheory\Saveyour\Contracts\Report\ProcessedFormReportInterface;
use WebTheory\Saveyour\Contracts\Report\ProcessedInputReportInterface;
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

    /**
     * @return $this
     */
    public function withShieldReport(FormShieldReportInterface $report): ProcessedFormReportBuilderInterface
    {
        $this->shieldReport = $report;

        return $this;
    }

    /**
     * @return $this
     */
    public function withInputReport(string $input, ProcessedInputReportInterface $report): ProcessedFormReportBuilderInterface
    {
        $this->inputReports[$input] = $report;

        return $this;
    }

    /**
     * @return $this
     */
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
