<?php

namespace WebTheory\Saveyour\Field\Abstracts;

use WebTheory\Saveyour\Contracts\Field\Selection\SelectionProviderInterface;

trait SelectionFieldTrait
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
