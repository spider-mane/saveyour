<?php

namespace WebTheory\Saveyour\Contracts;

interface FormShieldReportBuilderInterface
{
    public function withVerificationStatus(bool $status): FormShieldReportBuilderInterface;

    public function withRuleViolation(string $violation): FormShieldReportBuilderInterface;

    public function withRuleViolations(array $violations): FormShieldReportBuilderInterface;

    public function build(): FormShieldReportInterface;
}
