<?php

namespace WebTheory\Saveyour\Contracts;

interface ValidationReportInterface
{
    public function validationStatus(): bool;

    /**
     * @return array<int,string>
     */
    public function ruleViolations(): array;
}
