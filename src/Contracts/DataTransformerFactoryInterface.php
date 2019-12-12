<?php

namespace WebTheory\Saveyour\Contracts;

interface DataTransformerFactoryInterface
{
    /**
     *
     */
    public function create(array $args = []): DataTransformerInterface;
}
