<?php

namespace WebTheory\Saveyour\Contracts\Factory;

use WebTheory\Saveyour\Contracts\Data\FieldDataManagerInterface;

interface FieldDataManagerFactoryInterface
{
    public function create(array $args = []): FieldDataManagerInterface;
}
