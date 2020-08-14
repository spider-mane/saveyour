<?php

namespace WebTheory\Saveyour\Contracts;

interface DataFormatterFactoryInterface
{
    /**
     *
     */
    public function create(array $args = []): DataFormatterInterface;
}
