<?php

namespace WebTheory\Saveyour\Concerns;

use WebTheory\Saveyour\Contracts\SelectOptionsProviderInterface;

trait IsSimpleSelectionFieldTrait
{
    use IsSelectionFieldTrait;

    /**
     * @var SelectOptionsProviderInterface
     */
    protected $selectionProvider;

    /**
     *
     */
    protected function defineSelectionText($item)
    {
        return $this->selectionProvider->provideItemText($item);
    }
}
