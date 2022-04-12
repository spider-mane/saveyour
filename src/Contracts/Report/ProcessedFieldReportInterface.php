<?php

namespace WebTheory\Saveyour\Contracts\Report;

interface ProcessedFieldReportInterface
{
    public function sanitizedInputValue();

    public function updateAttempted(): bool;

    public function updateSuccessful(): bool;
}
