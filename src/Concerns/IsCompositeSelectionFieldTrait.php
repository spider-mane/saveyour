<?php

namespace WebTheory\Saveyour\Concerns;

use WebTheory\Saveyour\Contracts\CompositeFieldSelectionProviderInterface;

trait IsCompositeSelectionFieldTrait
{
    use IsSelectionFieldTrait;

    /**
     * @var CompositeFieldSelectionProviderInterface
     */
    protected $selectionProvider;

    /**
     *
     */
    protected function defineSelectionLabel($item)
    {
        return $this->selectionProvider->provideItemLabel($item);
    }

    /**
     *
     */
    protected function defineSelectionId($item)
    {
        return $this->selectionProvider->provideItemId($item);
    }
}
