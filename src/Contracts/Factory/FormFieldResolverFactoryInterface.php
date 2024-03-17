<?php

namespace WebTheory\Saveyour\Contracts\Factory;

use WebTheory\Factory\Interfaces\FlexFactoryInterface;
use WebTheory\Saveyour\Contracts\Field\FormFieldInterface;

interface FormFieldResolverFactoryInterface extends FlexFactoryInterface
{
    public function create(string $field, array $args = []): FormFieldInterface;
}
