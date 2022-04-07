<?php

namespace WebTheory\Saveyour\Contracts;

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
}
