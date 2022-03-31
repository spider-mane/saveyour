<?php

namespace WebTheory\Saveyour\Contracts;

interface FormFieldResolverFactoryInterface
{
    public function create(string $field, array $args = []): FormFieldInterface;
}
