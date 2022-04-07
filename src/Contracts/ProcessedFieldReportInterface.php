<?php

namespace WebTheory\Saveyour\Contracts;

interface ProcessedFieldReportInterface extends ValidationReportInterface
{
    public function sanitizedInputValue();

    public function updateAttempted(): bool;

    public function updateSuccessful(): bool;
}
