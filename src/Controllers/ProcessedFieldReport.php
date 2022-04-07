<?php

namespace WebTheory\Saveyour\Controllers;

use WebTheory\Saveyour\Contracts\ProcessedFieldReportInterface;
use WebTheory\Saveyour\Validation\ValidationReport;

class ProcessedFieldReport extends ValidationReport implements ProcessedFieldReportInterface
{
    protected $sanitizedInputValue;

    protected bool $updateAttempted = false;

    protected bool $updateSuccessful = false;

    public function __construct(
        $sanitizedInputValue = null,
        bool $updateAttempted = false,
        bool $updateSuccessful = false,
        bool $validationStatus = false,
        array $ruleViolations = []
    ) {
        parent::__construct($validationStatus, ...$ruleViolations);

        $this->sanitizedInputValue = $sanitizedInputValue;
        $this->updateAttempted = $updateAttempted;
        $this->updateSuccessful = $updateSuccessful;
    }

    /**
     * @var array<int,string>
     */
    protected array $ruleViolations = [];

    public function sanitizedInputValue()
    {
        return $this->sanitizedInputValue;
    }

    public function updateAttempted(): bool
    {
        return $this->updateAttempted;
    }

    public function updateSuccessful(): bool
    {
        return $this->updateSuccessful;
    }

    public function toArray()
    {
        return [
            'request_var_present' => $this->requestVarPresent,
            'sanitized_input_value' => $this->sanitizedInputValue,
            'update_attempted' => $this->updateAttempted,
            'update_successful' => $this->updateSuccessful,
            'validation_status' => $this->validationStatus,
            'rule_violations' => $this->ruleViolations,
        ];
    }

    public static function voided(): ProcessedFieldReportInterface
    {
        return new static(null, false, false, false, []);
    }
}
