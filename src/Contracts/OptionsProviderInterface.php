<?php

namespace WebTheory\Saveyour\Contracts;

interface OptionsProviderInterface extends SelectionProviderInterface
{
    /**
     * @param mixed
     *
     * @return string
     */
    public function defineSelectionText($selection): string;
}
