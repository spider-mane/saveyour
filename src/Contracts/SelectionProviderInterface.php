<?php

namespace WebTheory\Saveyour\Contracts;

interface SelectionProviderInterface
{
    /**
     *
     */
    public function provideSelectionData(): array;

    /**
     *
     */
    public function defineSelectionValue($selection): string;
}
