<?php

namespace WebTheory\Saveyour\Selections;

use WebTheory\Saveyour\Contracts\SelectionProviderInterface;

abstract class AbstractSelectionFromMap implements SelectionProviderInterface
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
    public function provideSelectionsData(): array
    {
        return $this->selection;
    }

    /**
     *
     */
    public function defineSelectionValue($item): string
    {
        return array_search($item, $this->selection);
    }
}
