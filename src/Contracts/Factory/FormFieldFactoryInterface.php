<?php

namespace WebTheory\Saveyour\Contracts\Factory;

use WebTheory\Saveyour\Contracts\Field\FormFieldInterface;

interface FormFieldFactoryInterface
{
    public function create(array $args = []): FormFieldInterface;
}
