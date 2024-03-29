<?php

namespace WebTheory\Saveyour\Contracts\Report\Builder;

use WebTheory\Saveyour\Contracts\Report\ValidationReportInterface;

interface ValidationReportBuilderInterface
{
    public function withValidationStatus(bool $status): ValidationReportBuilderInterface;

    public function withRuleViolation(string $violation): ValidationReportBuilderInterface;

    /**
     * @param array<int,string> $violations
     */
    public function withRuleViolations(array $violations): ValidationReportBuilderInterface;

    public function build(): ValidationReportInterface;
}
