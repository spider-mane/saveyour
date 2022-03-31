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

    protected function defineSelectionLabel($selection): string
    {
        return $this->selectionProvider->defineSelectionLabel($selection);
    }

    protected function defineSelectionId($selection): string
    {
        return $this->selectionProvider->defineSelectionId($selection);
    }
}
