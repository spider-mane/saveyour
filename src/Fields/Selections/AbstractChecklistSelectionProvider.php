<?php

namespace WebTheory\Saveyour\Fields\Selections;

use WebTheory\Saveyour\Contracts\SelectionProviderInterface;

abstract class AbstractChecklistSelectionProvider extends AbstractSelectionProvider implements SelectionProviderInterface
{
    /**
     *
     */
    protected function provideItem($item)
    {
        return [
            'value' => $this->provideItemValue($item),
            'label' => $this->provideItemLabel($item),
            'id' => $this->provideItemId($item)
        ];
    }

    /**
     *
     */
    abstract protected function provideItemValue($item): string;

    /**
     *
     */
    abstract protected function provideItemId($item): string;

    /**
     *
     */
    abstract protected function provideItemLabel($item): string;
}
