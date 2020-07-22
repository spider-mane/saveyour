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
     * Get the value of selectionProvider
     *
     * @return SelectionProviderInterface
     */
    public function getSelectionProvider(): SelectionProviderInterface
    {
        return $this->selectionProvider;
    }

    /**
     * Set the value of selectionProvider
     *
     * @param SelectionProviderInterface $selectionProvider
     *
     * @return self
     */
    public function setSelectionProvider(SelectionProviderInterface $selectionProvider)
    {
        $this->selectionProvider = $selectionProvider;

        return $this;
    }

    /**
     *
     */
    protected function getSelectionData()
    {
        return $this->selectionProvider->provideItemsAsRawData();
    }

    /**
     *
     */
    protected function defineSelectionValue($item)
    {
        return $this->selectionProvider->provideItemValue($item);
    }
}
