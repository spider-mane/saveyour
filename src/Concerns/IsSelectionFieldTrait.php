<?php

namespace WebTheory\Saveyour\Concerns;

use WebTheory\Saveyour\Contracts\SelectionProviderInterface;

trait IsSelectionFieldTrait
{
    protected function getSelectionData(): array
    {
        return $this->getSelectionProvider()->provideSelectionsData();
    }

    protected function defineSelectionValue($selection): string
    {
        return $this->getSelectionProvider()->defineSelectionValue($selection);
    }

    abstract protected function getSelectionProvider(): SelectionProviderInterface;
}
