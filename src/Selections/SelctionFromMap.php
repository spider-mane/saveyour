<?php

namespace WebTheory\Saveyour\Selections;

use WebTheory\Saveyour\Contracts\SelectionProviderInterface;

class SelectionFromMap implements SelectionProviderInterface
{
    /**
     * @var array
     */
    protected $selection = [];

    /**
     *
     */
    public function __construct(array $selection)
    {
        $this->selection = $selection;
    }

    /**
     * Get the value of selection
     *
     * @return array
     */
    public function getSelection(): array
    {
        return $this->selection;
    }

    /**
     *
     */
    public function provideItemsAsRawData(): array
    {
        return $this->selection;
    }

    /**
     *
     */
    public function provideItemValue($item): string
    {
        return array_search($item, $this->selection);
    }
}
