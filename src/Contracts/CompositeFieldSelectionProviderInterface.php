<?php

namespace WebTheory\Saveyour\Contracts;

interface CompositeFieldSelectionProviderInterface extends SelectionProviderInterface
{
    public function defineSelectionLabel($selection): string;

    public function defineSelectionId($selection): string;
}
