<?php

namespace WebTheory\Saveyour\Controllers;

use WebTheory\Saveyour\Contracts\ProcessedFieldReportBuilderInterface;
use WebTheory\Saveyour\Contracts\ProcessedFieldReportInterface;

class ProcessedFieldReportBuilder extends AbstractProcessedFieldReport implements ProcessedFieldReportBuilderInterface
{
    public function __construct(?ProcessedFieldReportInterface $previous = null)
    {
        if ($previous) {
            $this->requestVarPresent = $previous->requestVarPresent();
            $this->sanitizedInputValue = $previous->sanitizedInputValue();
            $this->updateAttempted = $previous->updateAttempted();
            $this->updateSuccessful = $previous->updateSuccessful();
            $this->validationStatus = $previous->validationStatus();
            $this->ruleViolations = $previous->ruleViolations();
        }
    }

    public function withRequestVarPresent(bool $result): ProcessedFieldReportBuilder
    {
        $this->requestVarPresent = $result;

        return $this;
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

    public function withValidationStatus(bool $status): ProcessedFieldReportBuilder
    {
        $this->validationStatus = $status;

        return $this;
    }

    public function withRuleViolation(string $violation): ProcessedFieldReportBuilder
    {
        $this->ruleViolations[] = $violation;

        return $this;
    }

    public function withRuleViolations(array $violations): ProcessedFieldReportBuilder
    {
        $this->ruleViolations = $violations;

        return $this;
    }

    public function build(): ProcessedFieldReportInterface
    {
        return new ProcessedFieldReport(
            $this->requestVarPresent,
            $this->sanitizedInputValue,
            $this->updateAttempted,
            $this->updateSuccessful,
            $this->validationStatus,
            $this->ruleViolations
        );
    }
}
