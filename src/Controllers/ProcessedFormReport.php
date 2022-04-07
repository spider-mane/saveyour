<?php

namespace WebTheory\Saveyour\Controllers;

use WebTheory\Saveyour\Contracts\FormShieldReportInterface;
use WebTheory\Saveyour\Contracts\ProcessedFormReportInterface;

class ProcessedFormReport implements ProcessedFormReportInterface
{
    protected FormShieldReportInterface $shieldReport;

    protected array $inputReports = [];

    protected array $processReports = [];

    public function __construct(FormShieldReportInterface $shieldReport, array $inputReports, array $processReports)
    {
        $this->shieldReport = $shieldReport;
        $this->inputReports = $inputReports;
        $this->processReports = $processReports;
    }

    public function shieldReport(): FormShieldReportInterface
    {
        return $this->shieldReport;
    }

    public function inputReports(): array
    {
        return $this->inputReports;
    }

    public function processReports(): array
    {
        return $this->processReports;
    }
}
