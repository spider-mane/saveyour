<?php

namespace WebTheory\Saveyour\Contracts;

use WebTheory\Saveyour\Contracts\FieldDataManagerInterface;

interface FieldDataManagerResolverFactoryInterface
{
    /**
     *
     */
    public function create(string $manager, array $args = []): FieldDataManagerInterface;
}
