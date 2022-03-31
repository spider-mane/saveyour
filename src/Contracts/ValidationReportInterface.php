<?php

namespace WebTheory\Saveyour\Contracts;

interface ValidationReportInterface
{
    public function validationStatus(): bool;

    public function ruleViolations(): array;
}
