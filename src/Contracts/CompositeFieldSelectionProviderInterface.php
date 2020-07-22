<?php

namespace WebTheory\Saveyour\Contracts;

interface CompositeFieldSelectionProviderInterface extends SelectionProviderInterface
{
    /**
     *
     */
    public function provideItemLabel($item): string;

    /**
     *
     */
    public function provideItemId($item): string;
}
