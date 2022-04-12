<?php

namespace WebTheory\Saveyour\Contracts\Report;

interface ProcessedInputReportInterface extends ValidationReportInterface, ProcessedFieldReportInterface
{
    public function requestVarPresent(): bool;

    public function rawInputValue(): ?string;
}
