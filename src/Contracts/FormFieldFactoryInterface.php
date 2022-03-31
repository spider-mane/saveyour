<?php

namespace WebTheory\Saveyour\Contracts;

interface FormFieldFactoryInterface
{
    public function create(array $args = []): FormFieldInterface;
}
