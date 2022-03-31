<?php

namespace WebTheory\Saveyour\Selections;

use WebTheory\Saveyour\Contracts\CompositeFieldSelectionProviderInterface;

class CompositeSelectionFromMap extends AbstractSelectionFromMap implements CompositeFieldSelectionProviderInterface
{
    public function defineSelectionId($item): string
    {
        return $item['id'];
    }

    public function defineSelectionLabel($item): string
    {
        return $item['label'];
    }
}
