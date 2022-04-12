<?php

namespace WebTheory\Saveyour\Contracts\Factory;

use WebTheory\Saveyour\Contracts\Data\FieldDataManagerInterface;

interface FieldDataManagerResolverFactoryInterface
{
    public function create(string $manager, array $args = []): FieldDataManagerInterface;
}
