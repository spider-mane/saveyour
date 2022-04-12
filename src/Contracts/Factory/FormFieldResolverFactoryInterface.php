<?php

namespace WebTheory\Saveyour\Contracts\Factory;

use WebTheory\Saveyour\Contracts\Field\FormFieldInterface;

interface FormFieldResolverFactoryInterface
{
    public function create(string $field, array $args = []): FormFieldInterface;
}
