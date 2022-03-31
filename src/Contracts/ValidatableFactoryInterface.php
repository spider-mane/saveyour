<?php

namespace WebTheory\Saveyour\Contracts;

use Respect\Validation\Validatable;

interface ValidatableFactoryInterface
{
    public function create(array $args = []): Validatable;
}
