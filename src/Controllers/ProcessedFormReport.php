<?php

namespace WebTheory\Saveyour\Controllers;

use WebTheory\Saveyour\Contracts\FormShieldReportInterface;
use WebTheory\Saveyour\Contracts\ProcessedFormReportInterface;

class ProcessedFormReport implements ProcessedFormReportInterface
{
    protected FormShieldReportInterface $shieldReport;

    protected array $fieldReports = [];

    protected array $processReports = [];

    public function __construct(FormShieldReportInterface $shieldReport, array $fieldReports, array $processReports)
    {
        $this->shieldReport = $shieldReport;
        $this->fieldReports = $fieldReports;
        $this->processReports = $processReports;
    }

    public function shieldReport(): FormShieldReportInterface
    {
        return $this->shieldReport;
    }

    public function fieldReports(): array
    {
        return $this->fieldReports;
    }

    public function processReports(): array
    {
        return $this->processReports;
    }
}
