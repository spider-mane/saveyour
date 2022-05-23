<?php

namespace WebTheory\Saveyour\Report\Builder;

use WebTheory\Saveyour\Contracts\Report\Builder\FormShieldReportBuilderInterface;
use WebTheory\Saveyour\Contracts\Report\FormShieldReportInterface;
use WebTheory\Saveyour\Report\FormShieldReport;

class FormShieldReportBuilder implements FormShieldReportBuilderInterface
{
    protected bool $status;

    protected array $violations = [];

    public function __construct(FormShieldReportInterface $previous = null)
    {
        if ($previous) {
            $this->status = $previous->verificationStatus();
            $this->violations = $previous->ruleViolations();
        }
    }

    /**
     * @return $this
     */
    public function withVerificationStatus(bool $status): FormShieldReportBuilderInterface
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return $this
     */
    public function withRuleViolation(string $name): FormShieldReportBuilderInterface
    {
        $this->violations[] = $name;

        return $this;
    }

    /**
     * @return $this
     */
    public function withRuleViolations(array $violations): FormShieldReportBuilderInterface
    {
        $this->violations = $violations;

        return $this;
    }

    public function build(): FormShieldReportInterface
    {
        return new FormShieldReport($this->status, ...$this->violations);
    }
}
