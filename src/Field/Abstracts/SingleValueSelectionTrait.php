<?php

namespace WebTheory\Saveyour\Field\Abstracts;

trait SingleValueSelectionTrait
{
    protected function isSelectionSelected(string $value): bool
    {
        return $value === $this->value;
    }
}
