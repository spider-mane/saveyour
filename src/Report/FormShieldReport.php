<?php

namespace WebTheory\Saveyour\Report;

use WebTheory\Saveyour\Contracts\Report\FormShieldReportInterface;

class FormShieldReport implements FormShieldReportInterface
{
    protected bool $verificationStatus;

    protected array $ruleViolations;

    public function __construct(bool $verificationStatus, string ...$ruleViolations)
    {
        $this->verificationStatus = $verificationStatus;
        $this->ruleViolations = $ruleViolations;
    }

    public function verificationStatus(): bool
    {
        return $this->verificationStatus;
    }

    public function ruleViolations(): array
    {
        return $this->ruleViolations;
    }
}
