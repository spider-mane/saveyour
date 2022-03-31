<?php

namespace WebTheory\Saveyour\Contracts;

interface FieldDataManagerResolverFactoryInterface
{
    public function create(string $manager, array $args = []): FieldDataManagerInterface;
}
