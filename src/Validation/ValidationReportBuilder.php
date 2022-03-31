<?php

namespace WebTheory\Saveyour\Validation;

use WebTheory\Saveyour\Contracts\ValidationReportBuilderInterface;
use WebTheory\Saveyour\Contracts\ValidationReportInterface;

class ValidationReportBuilder implements ValidationReportBuilderInterface
{
    protected bool $status;

    protected array $violations = [];

    public function validationStatus(): bool
    {
        return $this->status;
    }

    public function withValidationStatus(bool $status): ValidationReportBuilderInterface
    {
        $this->status = $status;

        return $this;
    }

    public function withRuleViolation(string $violation): ValidationReportBuilderInterface
    {
        $this->violations[] = $violation;

        return $this;
    }

    public function ruleViolations(): array
    {
        return $this->violations;
    }

    public function build(): ValidationReportInterface
    {
        return new ValidationReport($this->status, ...$this->violations);
    }
}
