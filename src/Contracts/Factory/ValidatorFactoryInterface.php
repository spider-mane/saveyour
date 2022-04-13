<?php

namespace WebTheory\Saveyour\Contracts\Factory;

use WebTheory\Saveyour\Contracts\Validation\ValidatorInterface;

interface ValidatorFactoryInterface
{
    public function create(array $args = []): ValidatorInterface;
}
