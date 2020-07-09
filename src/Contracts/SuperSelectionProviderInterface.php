<?php

namespace WebTheory\Saveyour\Contracts;

interface SuperSelectionProviderInterface
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
