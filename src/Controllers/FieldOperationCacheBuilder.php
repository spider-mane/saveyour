<?php

namespace WebTheory\Saveyour\Controllers;

use WebTheory\Saveyour\Contracts\FieldOperationCacheInterface;

class FieldOperationCacheBuilder extends AbstractFieldOperationCache implements FieldOperationCacheInterface
{
    /**
     *
     */
    public function withRequestVarPresent(bool $result)
    {
        $this->results['request_var_present'] = $result;

        return $this;
    }

    /**
     *
     */
    public function withSanitizedInputValue($value)
    {
        $this->results['sanitized_input_value'] = $value;

        return $this;
    }

    /**
     *
     */
    public function withUpdateAttempted(bool $result)
    {
        $this->results['update_attempted'] = $result;

        return $this;
    }

    /**
     *
     */
    public function withUpdateSuccessful(bool $result)
    {
        $this->results['update_successful'] = $result;

        return $this;
    }

    /**
     *
     */
    public function withRuleViolations(array $violations)
    {
        $this->results['rule_violations'] = $violations;
    }
}
