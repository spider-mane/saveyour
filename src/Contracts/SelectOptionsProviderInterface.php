<?php

namespace WebTheory\Saveyour\Contracts;

interface SelectOptionsProviderInterface extends SuperSelectionProviderInterface
{
    /**
     *
     */
    public function provideItemContent($item): string;
}
