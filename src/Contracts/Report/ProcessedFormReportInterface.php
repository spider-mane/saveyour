<?php

namespace WebTheory\Saveyour\Contracts\Report;

interface ProcessedFormReportInterface
{
    public function shieldReport(): FormShieldReportInterface;

    /**
     * @return array<string,ProcessedInputReportInterface>
     */
    public function inputReports(): array;

    /**
     * @return array<string,FormProcessReportInterface>
     */
    public function processReports(): array;

    public function submissionIsValid(): bool;
}
