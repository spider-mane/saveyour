<?php

namespace WebTheory\Saveyour\Contracts;

interface ProcessedFieldReportInterface
{
    public function sanitizedInputValue();

    public function updateAttempted(): bool;

    public function updateSuccessful(): bool;
}
