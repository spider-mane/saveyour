<?php

namespace WebTheory\Saveyour\Fields\Selections;

use WebTheory\Saveyour\Contracts\CompositeFieldSelectionProviderInterface;

class CompositeSelectionFromMap extends SelectionFromMap implements CompositeFieldSelectionProviderInterface
{
    /**
     *
     */
    public function provideItemId($item): string
    {
        return $item['id'];
    }

    /**
     *
     */
    public function provideItemLabel($item): string
    {
        return $item['label'];
    }
}
