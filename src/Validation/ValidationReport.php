<?php

namespace WebTheory\Saveyour\Validation;

use WebTheory\Saveyour\Contracts\ValidationReportInterface;

class ValidationReport implements ValidationReportInterface
{
    protected bool $status;

    protected array $violations = [];

    public function __construct(bool $status, string ...$violations)
    {
        $this->status = $status;
        $this->violations = $violations;
    }

    public function validationStatus(): bool
    {
        return $this->status;
    }

    /**
     * @return array<int,string>
     */
    public function ruleViolations(): array
    {
        return $this->violations;
    }

    public static function voided(): ValidationReportInterface
    {
        return new static(false);
    }
}
