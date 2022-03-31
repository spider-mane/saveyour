<?php

namespace WebTheory\Saveyour\Controllers;

use JsonSerializable;
use WebTheory\Saveyour\Contracts\FieldOperationCacheInterface;

abstract class AbstractFieldOperationCache implements FieldOperationCacheInterface, JsonSerializable
{
    protected bool $requestVarPresent = false;

    protected $sanitizedInputValue;

    protected bool $updateAttempted = false;

    protected bool $updateSuccessful = false;

    protected bool $validationStatus = false;

    /**
     * @var array<int,string>
     */
    protected array $ruleViolations = [];

    public function requestVarPresent(): bool
    {
        return $this->requestVarPresent;
    }

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

    public function ruleViolations(): array
    {
        return $this->ruleViolations;
    }

    public function validationStatus(): bool
    {
        return $this->validationStatus;
    }

    public function jsonSerialize()
    {
        return $this->toArray();
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

    public function toJson()
    {
        return json_encode($this->jsonSerialize());
    }
}
