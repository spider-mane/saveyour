<?php

namespace WebTheory\Saveyour\Fields\Selections;

use WebTheory\Saveyour\Contracts\SelectionProviderInterface;

abstract class AbstractSelectionProvider implements SelectionProviderInterface
{
    /**
     *
     */
    public function getSelection(): array
    {
        $selection = [];

        foreach ($this->provideItemsAsRawData() as $item) {
            $selection[$this->provideItemKey($item)] = $this->provideItem($item);
        }

        return $selection;
    }

    /**
     *
     */
    abstract protected function provideItemsAsRawData(): array;

    /**
     *
     */
    abstract protected function provideItemKey($item): string;

    /**
     * @var mixed
     */
    abstract protected function provideItem($item);
}
