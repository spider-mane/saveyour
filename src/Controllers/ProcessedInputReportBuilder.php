<?php

namespace WebTheory\Saveyour\Controllers;

use WebTheory\Saveyour\Contracts\ProcessedFieldReportBuilderInterface;
use WebTheory\Saveyour\Contracts\ProcessedInputReportBuilderInterface;
use WebTheory\Saveyour\Contracts\ProcessedInputReportInterface;

class ProcessedInputReportBuilder extends ProcessedFieldReportBuilder implements ProcessedInputReportBuilderInterface
{
    protected bool $requestVarPresent;

    protected ?string $rawInputValue = null;

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

    public function withRequestVarPresent(bool $result): ProcessedFieldReportBuilderInterface
    {
        $this->requestVarPresent = $result;

        return $this;
    }

    public function withRawInputValue(string $value): ProcessedInputReportBuilderInterface
    {
        $this->rawInputValue = $value;

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
