<?php

namespace WebTheory\Saveyour\Controllers;

use WebTheory\Saveyour\Contracts\ProcessedFieldReportBuilderInterface;
use WebTheory\Saveyour\Contracts\ProcessedFieldReportInterface;
use WebTheory\Saveyour\Validation\ValidationReportBuilder;

class ProcessedFieldReportBuilder extends ValidationReportBuilder implements ProcessedFieldReportBuilderInterface
{
    protected $sanitizedInputValue = null;

    protected bool $updateAttempted = false;

    protected bool $updateSuccessful = false;

    public function __construct(?ProcessedFieldReportInterface $previous = null)
    {
        if ($previous) {
            $this->sanitizedInputValue = $previous->sanitizedInputValue();
            $this->updateAttempted = $previous->updateAttempted();
            $this->updateSuccessful = $previous->updateSuccessful();
            $this->validationStatus = $previous->validationStatus();
            $this->ruleViolations = $previous->ruleViolations();
        }
    }

    public function withSanitizedInputValue($value): ProcessedFieldReportBuilder
    {
        $this->sanitizedInputValue = $value;

        return $this;
    }

    public function withUpdateAttempted(bool $result): ProcessedFieldReportBuilder
    {
        $this->updateAttempted = $result;

        return $this;
    }

    public function withUpdateSuccessful(bool $result): ProcessedFieldReportBuilder
    {
        $this->updateSuccessful = $result;

        return $this;
    }

    public function build(): ProcessedFieldReportInterface
    {
        return new ProcessedFieldReport(
            $this->sanitizedInputValue,
            $this->updateAttempted,
            $this->updateSuccessful,
            $this->validationStatus,
            $this->ruleViolations
        );
    }
}
