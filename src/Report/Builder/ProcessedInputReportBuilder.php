<?php

namespace WebTheory\Saveyour\Report\Builder;

use WebTheory\Saveyour\Contracts\Report\Builder\ProcessedInputReportBuilderInterface;
use WebTheory\Saveyour\Contracts\Report\ProcessedInputReportInterface;
use WebTheory\Saveyour\Report\ProcessedInputReport;

class ProcessedInputReportBuilder extends ProcessedFieldReportBuilder implements ProcessedInputReportBuilderInterface
{
    protected bool $requestVarPresent;

    protected string $rawInputValue;

    protected bool $validationStatus;

    protected array $ruleViolations;

    public function __construct(ProcessedInputReportInterface $previous = null)
    {
        if ($previous) {
            $this->requestVarPresent = $previous->requestVarPresent();
            $this->rawInputValue = $previous->rawInputValue();
            $this->sanitizedInputValue = $previous->sanitizedInputValue();
            $this->updateAttempted = $previous->updateAttempted();
            $this->updateSuccessful = $previous->updateSuccessful();
            $this->validationStatus = $previous->validationStatus();
            $this->ruleViolations = $previous->ruleViolations();
        }
    }

    /**
     * @return $this
     */
    public function withRequestVarPresent(bool $result): ProcessedInputReportBuilderInterface
    {
        $this->requestVarPresent = $result;

        return $this;
    }

    /**
     * @return $this
     */
    public function withRawInputValue(string $value): ProcessedInputReportBuilderInterface
    {
        $this->rawInputValue = $value;

        return $this;
    }

    /**
     * @return $this
     */
    public function withValidationStatus(bool $result): ProcessedInputReportBuilderInterface
    {
        $this->validationStatus = $result;

        return $this;
    }

    /**
     * @return $this
     */
    public function withRuleViolations(array $violations): ProcessedInputReportBuilderInterface
    {
        $this->ruleViolations = $violations;

        return $this;
    }

    /**
     * @return $this
     */
    public function withRuleViolation(string $violation): ProcessedInputReportBuilderInterface
    {
        $this->ruleViolations[] = $violation;

        return $this;
    }

    public function build(): ProcessedInputReportInterface
    {
        return new ProcessedInputReport(
            $this->requestVarPresent,
            $this->rawInputValue,
            $this->sanitizedInputValue,
            $this->updateAttempted,
            $this->updateSuccessful,
            $this->validationStatus,
            $this->ruleViolations
        );
    }
}
