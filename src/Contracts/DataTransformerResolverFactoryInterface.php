<?php

namespace WebTheory\Saveyour\Contracts;

interface DataTransformerResolverFactoryInterface
{
    /**
     *
     */
    public function create(string $name, array $args = []): DataTransformerInterface;
}
