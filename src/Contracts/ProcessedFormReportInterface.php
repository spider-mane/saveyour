<?php

namespace WebTheory\Saveyour\Contracts;

interface ProcessedFormReportInterface
{
    public function shieldReport(): FormShieldReportInterface;

    /**
     * @return array<string,FieldOperationCacheInterface>
     */
    public function fieldReports(): array;

    /**
     * @return array<string,FormDataProcessingCacheInterface>
     */
    public function processReports(): array;
}
