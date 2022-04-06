<?php

namespace WebTheory\Saveyour\Shield;

use WebTheory\Saveyour\Contracts\FormShieldReportBuilderInterface;
use WebTheory\Saveyour\Contracts\FormShieldReportInterface;

class FormShieldReportBuilder extends FormShieldReport implements FormShieldReportBuilderInterface
{
    public function __construct(FormShieldReportInterface $previous = null)
    {
        if ($previous) {
            $this->verificationStatus = $previous->verificationStatus();
            $this->ruleViolations = $previous->ruleViolations();
        }
    }

    public function withVerificationStatus(bool $status): FormShieldReportBuilderInterface
    {
        $this->verificationStatus = $status;

        return $this;
    }

    public function withRuleViolation(string $name): FormShieldReportBuilderInterface
    {
        $this->ruleViolations[] = $name;

        return $this;
    }

    public function withRuleViolations(array $violations): FormShieldReportBuilderInterface
    {
        $this->ruleViolations = $violations;

        return $this;
    }

    public function build(): FormShieldReportInterface
    {
        return new FormShieldReport(
            $this->verificationStatus,
            $this->ruleViolations
        );
    }
}
