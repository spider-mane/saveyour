<?php

namespace WebTheory\Saveyour\Concerns;

trait SingleValueSelectionTrait
{
    protected function isSelectionSelected(string $value): bool
    {
        return $value === $this->value;
    }
}
