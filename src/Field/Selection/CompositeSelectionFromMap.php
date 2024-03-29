<?php

namespace WebTheory\Saveyour\Field\Selection;

use WebTheory\Saveyour\Contracts\Field\Selection\CompositeFieldSelectionProviderInterface;
use WebTheory\Saveyour\Field\Selection\Abstracts\AbstractSelectionFromMap;

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
