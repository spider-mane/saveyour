<?php

namespace WebTheory\Saveyour\Contracts;

interface CompositeFieldSelectionProviderInterface extends SelectionProviderInterface
{
    /**
     *
     */
    public function provideItemLabel($selection): string;

    /**
     *
     */
    public function provideItemId($selection): string;
}
