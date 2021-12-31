<?php

namespace WebTheory\Saveyour\Contracts;

interface FieldOperationCacheBuilderInterface extends FieldOperationCacheInterface
{
    /**
     *
     */
    public function withRequestVarPresent(bool $result): FieldOperationCacheBuilderInterface;

    /**
     *
     */
    public function withSanitizedInputValue($value): FieldOperationCacheBuilderInterface;

    /**
     *
     */
    public function withUpdateAttempted(bool $result): FieldOperationCacheBuilderInterface;

    /**
     *
     */
    public function withUpdateSuccessful(bool $result): FieldOperationCacheBuilderInterface;

    /**
     *
     */
    public function withRuleViolations(array $violations): FieldOperationCacheBuilderInterface;
}
