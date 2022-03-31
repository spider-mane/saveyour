<?php

namespace WebTheory\Saveyour\Contracts;

interface FieldOperationCacheInterface extends ValidationReportInterface
{
    public function requestVarPresent(): bool;

    public function sanitizedInputValue();

    public function updateAttempted(): bool;

    public function updateSuccessful(): bool;
}
