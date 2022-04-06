<?php

namespace WebTheory\Saveyour\Contracts;

interface ProcessedFormReportInterface
{
    public function shieldReport(): FormShieldReportInterface;

    /**
     * @return array<string,ProcessedFieldReportInterface>
     */
    public function fieldReports(): array;

    /**
     * @return array<string,FormProcessReportInterface>
     */
    public function processReports(): array;
}
