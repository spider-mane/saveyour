<?php

namespace WebTheory\Saveyour\Field\Selection;

use WebTheory\Saveyour\Contracts\Field\Selection\OptionsProviderInterface;
use WebTheory\Saveyour\Field\Selection\Abstracts\AbstractSelectionFromMap;

class SelectOptionsFromMap extends AbstractSelectionFromMap implements OptionsProviderInterface
{
    public function defineSelectionText($item): string
    {
        return $item;
    }
}
