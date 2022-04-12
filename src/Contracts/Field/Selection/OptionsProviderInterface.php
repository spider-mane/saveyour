<?php

namespace WebTheory\Saveyour\Contracts\Field\Selection;

interface OptionsProviderInterface extends SelectionProviderInterface
{
    /**
     * @param mixed
     *
     * @return string
     */
    public function defineSelectionText($selection): string;
}
