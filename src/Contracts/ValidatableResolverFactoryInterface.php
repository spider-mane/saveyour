<?php

namespace WebTheory\Saveyour\Contracts;

use Respect\Validation\Validatable;

interface ValidatableResolverFactoryInterface
{
    public function create(string $name, array $args = []): Validatable;
}
