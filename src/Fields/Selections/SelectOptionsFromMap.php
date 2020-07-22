<?php

namespace WebTheory\Saveyour\Fields\Selections;

use WebTheory\Saveyour\Contracts\SelectOptionsProviderInterface;

class SelectOptionsFromMap extends SelectionFromMap implements SelectOptionsProviderInterface
{
    /**
     *
     */
    public function provideItemText($item): string
    {
        return $item;
    }
}
