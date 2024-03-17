<?php

namespace WebTheory\Saveyour\Contracts\Factory;

use WebTheory\Factory\Interfaces\FlexFactoryInterface;
use WebTheory\Saveyour\Contracts\Data\FieldDataManagerInterface;

interface FieldDataManagerResolverFactoryInterface extends FlexFactoryInterface
{
    public function create(string $manager, array $args = []): FieldDataManagerInterface;
}
