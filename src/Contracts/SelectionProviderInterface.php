<?php

namespace WebTheory\Saveyour\Contracts;

interface SelectionProviderInterface
{
    /**
     *
     */
    public function provideSelectionsData(): array;

    /**
     *
     */
    public function defineSelectionValue($selection): string;
}
