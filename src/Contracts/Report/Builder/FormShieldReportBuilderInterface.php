<?php

namespace WebTheory\Saveyour\Contracts\Report\Builder;

use WebTheory\Saveyour\Contracts\Report\FormShieldReportInterface;

interface FormShieldReportBuilderInterface
{
    public function withVerificationStatus(bool $status): FormShieldReportBuilderInterface;

    public function withRuleViolation(string $violation): FormShieldReportBuilderInterface;

    public function withRuleViolations(array $violations): FormShieldReportBuilderInterface;

    public function build(): FormShieldReportInterface;
}
