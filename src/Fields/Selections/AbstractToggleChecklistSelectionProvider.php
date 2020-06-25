<?php

namespace WebTheory\Saveyour\Fields\Selections;

use WebTheory\Saveyour\Contracts\SelectionProviderInterface;

abstract class AbstractToggleChecklistSelectionProvider extends AbstractChecklistSelectionProvider implements SelectionProviderInterface
{
    /**
     *
     */
    protected function provideItem($item)
    {
        return [
            'name' => $this->provideItemName($item),
            'value' => $this->provideItemValue($item),
            'label' => $this->provideItemLabel($item),
            'id' => $this->provideItemId($item)
        ];
    }

    /**
     *
     */
    abstract protected function provideItemName($item): string;
}
