<?php

namespace WebTheory\Saveyour\Contracts\Field\Selection;

interface SelectionProviderInterface
{
    public function provideSelectionsData(): array;

    public function defineSelectionValue($selection): string;
}
