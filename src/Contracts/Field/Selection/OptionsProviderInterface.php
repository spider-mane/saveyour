<?php

namespace WebTheory\Saveyour\Contracts\Field\Selection;

interface OptionsProviderInterface extends SelectionProviderInterface
{
    public function defineSelectionText($selection): string;
}
