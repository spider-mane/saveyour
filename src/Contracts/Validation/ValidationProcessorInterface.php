<?php

namespace WebTheory\Saveyour\Contracts\Validation;

interface ValidationProcessorInterface
{
    public function handleRuleViolation(string $rule): void;

    public function returnOnFailure(): mixed;
}
