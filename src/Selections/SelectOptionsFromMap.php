<?php

namespace WebTheory\Saveyour\Selections;

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
