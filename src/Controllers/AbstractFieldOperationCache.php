<?php

namespace WebTheory\Saveyour\Controllers;

use JsonSerializable;
use WebTheory\Saveyour\Contracts\FieldOperationCacheInterface;

abstract class AbstractFieldOperationCache implements FieldOperationCacheInterface, JsonSerializable
{
    /**
     *
     */
    protected $results = [
        'request_var_present' => false,
        'sanitized_input_value' => null,
        'update_attempted' => false,
        'update_successful' => false,
        'rule_violations' => [],
    ];

    /**
     * @var bool
     */
    protected $requestVarPresent = false;

    /**
     * @var mixed
     */
    protected $sanitizedInputValue;

    /**
     * @var bool
     */
    protected $updateAttempted = false;

    /**
     * @var bool
     */
    protected $updateSuccessful = false;

    /**
     * @var array
     */
    protected $ruleViolations = [];

    /**
     *
     */
    public function requestVarPresent(): bool
    {
        return $this->results['request_var_present'];
    }

    /**
     *
     */
    public function sanitizedInputValue()
    {
        return $this->results['sanitized_input_value'];
    }

    /**
     *
     */
    public function updateAttempted(): bool
    {
        return $this->results['update_attempted'];
    }

    /**
     *
     */
    public function updateSuccessful(): bool
    {
        return $this->results['update_successful'];
    }

    /**
     *
     */
    public function ruleViolations(): array
    {
        return $this->results['rule_violations'];
    }

    /**
     *
     */
    public function toArray()
    {
        return (array) $this;
    }

    /**
     *
     */
    public function toJson()
    {
        return json_encode($this);
    }

    /**
     *
     */
    public function jsonSerialize()
    {
        return $this;
    }
}
