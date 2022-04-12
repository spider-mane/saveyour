<?php

namespace WebTheory\Saveyour\Report;

use WebTheory\Saveyour\Contracts\ValidationReportInterface;

class ValidationReport implements ValidationReportInterface
{
    protected bool $validationStatus;

    protected array $ruleViolations = [];

    public function __construct(bool $status, string ...$violations)
    {
        $this->validationStatus = $status;
        $this->ruleViolations = $violations;
    }

    public function validationStatus(): bool
    {
        return $this->validationStatus;
    }

    /**
     * @return array<int,string>
     */
    public function ruleViolations(): array
    {
        return $this->ruleViolations;
    }

    public static function voided(): ValidationReportInterface
    {
        return new static(false);
    }
}
