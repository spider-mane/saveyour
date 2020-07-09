<?php

namespace WebTheory\Saveyour\Contracts;

interface CompositeFieldSelectionProviderInterface extends SuperSelectionProviderInterface
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
