<?php

namespace WebTheory\Saveyour\Controllers;

use ArrayAccess;
use JsonSerializable;
use WebTheory\Saveyour\Concerns\ImmutableObjectTrait;
use WebTheory\Saveyour\Contracts\FieldOperationCacheInterface;

class FieldOperationCache extends AbstractFieldOperationCache implements FieldOperationCacheInterface, ArrayAccess, JsonSerializable
{
    use ImmutableObjectTrait;

    /**
     *
     */
    public function __construct(
        bool $requestVarPresent,
        $sanitizedInputValue,
        bool $updateAttempted,
        bool $updateSuccessful,
        array $ruleViolations
    ) {
        $this->results['request_var_present'] = $requestVarPresent;
        $this->results['sanitized_input_value'] = $sanitizedInputValue;
        $this->results['update_attempted'] = $updateAttempted;
        $this->results['update_successful'] = $updateSuccessful;
        $this->results['rule_violations'] = $ruleViolations;
    }

    /**
     *
     */
    public function toArray()
    {
        return $this->results;
    }

    /**
     *
     */
    public function offsetExists($offset)
    {
        return isset($this->results[$offset]);
    }

    /**
     *
     */
    public function offsetGet($offset)
    {
        return $this->results[$offset];
    }

    /**
     *
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
