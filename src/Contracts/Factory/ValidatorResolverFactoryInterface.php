<?php

namespace WebTheory\Saveyour\Contracts\Factory;

use WebTheory\Saveyour\Contracts\Validation\ValidatorInterface;

interface ValidatorResolverFactoryInterface
{
    public function create(string $name, array $args = []): ValidatorInterface;
}
