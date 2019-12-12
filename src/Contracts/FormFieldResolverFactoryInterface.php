<?php

namespace WebTheory\Saveyour\Contracts;

use WebTheory\Saveyour\Contracts\FormFieldInterface;

interface FormFieldResolverFactoryInterface
{
    /**
     *
     */
    public function create(string $field, array $args = []): FormFieldInterface;
}
