<?php

namespace WebTheory\Saveyour\Contracts;

interface ProcessedInputReportInterface extends ProcessedFieldReportInterface
{
    public function requestVarPresent(): bool;

    public function rawInputValue(): ?string;
}
