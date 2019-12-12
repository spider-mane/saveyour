<?php

namespace WebTheory\Saveyour\Controllers;

use WebTheory\Saveyour\Contracts\FieldOperationCacheInterface;

abstract class AbstractFieldOperationCache implements FieldOperationCacheInterface
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
}
