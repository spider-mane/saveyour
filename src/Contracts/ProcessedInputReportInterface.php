<?php

namespace WebTheory\Saveyour\Contracts;

interface ProcessedInputReportInterface extends ValidationReportInterface, ProcessedFieldReportInterface
{
    public function requestVarPresent(): bool;

    public function rawInputValue(): ?string;
}
