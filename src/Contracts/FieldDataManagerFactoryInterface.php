<?php

namespace WebTheory\Saveyour\Contracts;

interface FieldDataManagerFactoryInterface
{
    public function create(array $args = []): FieldDataManagerInterface;
}
