<?php

namespace WebTheory\Saveyour\Validation;

use WebTheory\Saveyour\Contracts\Validation\ValidationProcessorInterface;

class LazyValidationProcessor implements ValidationProcessorInterface
{
    public function returnOnFailure(): mixed
    {
        return null;
    }

    public function handleRuleViolation(string $rule): void
    {
        return;
    }
}
