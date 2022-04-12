<?php

namespace WebTheory\Saveyour\Field\Selection;

use WebTheory\Saveyour\Contracts\OptionsProviderInterface;

class SelectOptionsFromMap extends AbstractSelectionFromMap implements OptionsProviderInterface
{
    public function defineSelectionText($item): string
    {
        return $item;
    }
}
