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
    protected function defineSelectionLabel($selection)
    {
        return $this->selectionProvider->defineSelectionLabel($selection);
    }

    /**
     *
     */
    protected function defineSelectionId($selection)
    {
        return $this->selectionProvider->defineSelectionId($selection);
    }
}
