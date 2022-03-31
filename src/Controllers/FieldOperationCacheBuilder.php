<?php

namespace WebTheory\Saveyour\Controllers;

use WebTheory\Saveyour\Contracts\FieldOperationCacheBuilderInterface;
use WebTheory\Saveyour\Contracts\FieldOperationCacheInterface;

class FieldOperationCacheBuilder extends AbstractFieldOperationCache implements FieldOperationCacheBuilderInterface
{
    public function __construct(?FieldOperationCacheInterface $previous = null)
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

    public function withRequestVarPresent(bool $result): FieldOperationCacheBuilder
    {
        $this->requestVarPresent = $result;

        return $this;
    }

    public function withSanitizedInputValue($value): FieldOperationCacheBuilder
    {
        $this->sanitizedInputValue = $value;

        return $this;
    }

    public function withUpdateAttempted(bool $result): FieldOperationCacheBuilder
    {
        $this->updateAttempted = $result;

        return $this;
    }

    public function withUpdateSuccessful(bool $result): FieldOperationCacheBuilder
    {
        $this->updateSuccessful = $result;

        return $this;
    }

    public function withValidationStatus(bool $status): FieldOperationCacheBuilder
    {
        $this->validationStatus = $status;

        return $this;
    }

    public function withRuleViolation(string $violation): FieldOperationCacheBuilder
    {
        $this->ruleViolations[] = $violation;

        return $this;
    }

    public function withRuleViolations(array $violations): FieldOperationCacheBuilder
    {
        $this->ruleViolations = $violations;

        return $this;
    }

    public function build(): FieldOperationCacheInterface
    {
        return new FieldOperationCache(
            $this->requestVarPresent,
            $this->sanitizedInputValue,
            $this->updateAttempted,
            $this->updateSuccessful,
            $this->validationStatus,
            $this->ruleViolations
        );
    }
}
