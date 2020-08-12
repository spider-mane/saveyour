<?php

namespace WebTheory\Saveyour\Concerns;

use WebTheory\Saveyour\Contracts\SelectionProviderInterface;

trait IsSelectionFieldTrait
{
    /**
     * @var SelectionProviderInterface
     */
    protected $selectionProvider;

    /**
     *
     */
    protected function getSelectionData()
    {
        return $this->selectionProvider->provideSelectionData();
    }

    /**
     *
     */
    protected function defineSelectionValue($selection)
    {
        return $this->selectionProvider->defineSelectionValue($selection);
    }
}
