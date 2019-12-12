<?php

namespace WebTheory\Saveyour\Contracts;

interface FieldOperationCacheInterface
{
    /**
     *
     */
    public function requestVarPresent(): bool;

    /**
     *
     */
    public function sanitizedInputValue();

    /**
     *
     */
    public function updateAttempted(): bool;

    /**
     *
     */
    public function updateSuccessful(): bool;

    /**
     *
     */
    public function ruleViolations(): array;
}
