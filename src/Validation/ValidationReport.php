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

    public function getStatus(): bool
    {
        return $this->status;
    }

    public function getViolations(): array
    {
        return $this->violations;
    }
}
