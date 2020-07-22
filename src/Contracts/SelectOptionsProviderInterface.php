<?php

namespace WebTheory\Saveyour\Contracts;

interface SelectOptionsProviderInterface extends SelectionProviderInterface
{
    /**
     * @param mixed
     *
     * @return string
     */
    public function provideItemText($item): string;
}
