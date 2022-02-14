<?php

namespace WebTheory\Saveyour\Validation;

use WebTheory\Saveyour\Contracts\ValidationReportBuilderInterface;
use WebTheory\Saveyour\Contracts\ValidationReportInterface;

class ValidationReportBuilder implements ValidationReportBuilderInterface
{
    protected bool $status;

    protected array $violations = [];

    public function status(bool $validationStatus)
    {
        $this->status = $validationStatus;
    }

    public function violation(string $violation)
    {
        $this->violations[] = $violation;
    }

    public function build(): ValidationReportInterface
    {
        return new ValidationReport($this->status, ...$this->violations);
    }
}
