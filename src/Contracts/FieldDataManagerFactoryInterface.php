<?php

namespace WebTheory\Saveyour\Contracts;

use WebTheory\Saveyour\Contracts\FieldDataManagerInterface;

interface FieldDataManagerFactoryInterface
{
    /**
     *
     */
    public function create(array $args = []): FieldDataManagerInterface;
}
