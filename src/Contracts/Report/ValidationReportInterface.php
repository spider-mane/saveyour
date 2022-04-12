<?php

namespace WebTheory\Saveyour\Contracts\Report;

interface ValidationReportInterface
{
    public function validationStatus(): bool;

    /**
     * @return array<int,string>
     */
    public function ruleViolations(): array;
}
