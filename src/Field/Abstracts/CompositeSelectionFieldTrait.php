<?php

namespace WebTheory\Saveyour\Field\Abstracts;

use WebTheory\Saveyour\Contracts\CompositeFieldSelectionProviderInterface;

trait CompositeSelectionFieldTrait
{
    use SelectionFieldTrait;

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
