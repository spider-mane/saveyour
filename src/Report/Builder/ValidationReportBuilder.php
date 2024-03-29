<?php

namespace WebTheory\Saveyour\Report\Builder;

use WebTheory\Saveyour\Contracts\Report\Builder\ValidationReportBuilderInterface;
use WebTheory\Saveyour\Contracts\Report\ValidationReportInterface;
use WebTheory\Saveyour\Report\ValidationReport;

class ValidationReportBuilder implements ValidationReportBuilderInterface
{
    protected bool $validationStatus;

    protected array $ruleViolations = [];

    /**
     * @return $this
     */
    public function withValidationStatus(bool $status): ValidationReportBuilderInterface
    {
        $this->validationStatus = $status;

        return $this;
    }

    /**
     * @return $this
     */
    public function withRuleViolation(string $violation): ValidationReportBuilderInterface
    {
        $this->ruleViolations[] = $violation;

        return $this;
    }

    /**
     * @return $this
     */
    public function withRuleViolations(array $violations): ValidationReportBuilderInterface
    {
        array_map([$this, 'withRuleViolation'], $violations);

        return $this;
    }

    public function build(): ValidationReportInterface
    {
        return new ValidationReport($this->validationStatus, ...$this->ruleViolations);
    }
}
