<?php

namespace WebTheory\Saveyour\Contracts\Factory;

use Respect\Validation\Validatable;

interface ValidatableFactoryInterface
{
    public function create(array $args = []): Validatable;
}
