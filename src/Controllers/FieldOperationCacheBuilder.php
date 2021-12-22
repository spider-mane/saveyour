<?php

namespace WebTheory\Saveyour\Controllers;

use WebTheory\Saveyour\Contracts\FieldOperationCacheBuilderInterface;
use WebTheory\Saveyour\Contracts\FieldOperationCacheInterface;

class FieldOperationCacheBuilder extends AbstractFieldOperationCache implements FieldOperationCacheBuilderInterface
{
    /**
     *
     */
    public function withRequestVarPresent(bool $result): FieldOperationCacheBuilderInterface
    {
        $this->results['request_var_present'] = $result;

        return $this;
    }

    /**
     *
     */
    public function withSanitizedInputValue($value): FieldOperationCacheBuilderInterface
    {
        $this->results['sanitized_input_value'] = $value;

        return $this;
    }

    /**
     *
     */
    public function withUpdateAttempted(bool $result): FieldOperationCacheBuilderInterface
    {
        $this->results['update_attempted'] = $result;

        return $this;
    }

    /**
     *
     */
    public function withUpdateSuccessful(bool $result): FieldOperationCacheBuilderInterface
    {
        $this->results['update_successful'] = $result;

        return $this;
    }

    /**
     *
     */
    public function withRuleViolations(array $violations): FieldOperationCacheBuilderInterface
    {
        $this->results['rule_violations'] = $violations;

        return $this;
    }
}
