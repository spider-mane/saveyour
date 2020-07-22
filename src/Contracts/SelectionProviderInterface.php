<?php

namespace WebTheory\Saveyour\Contracts;

interface SelectionProviderInterface
{
    /**
     *
     */
    public function provideItemsAsRawData(): array;

    /**
     *
     */
    public function provideItemValue($item): string;
}
