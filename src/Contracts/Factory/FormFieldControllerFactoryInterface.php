<?php

namespace WebTheory\Saveyour\Contracts\Factory;

use WebTheory\Factory\Interfaces\FixedFactoryInterface;
use WebTheory\Saveyour\Contracts\Controller\FormFieldControllerInterface;

interface FormFieldControllerFactoryInterface extends FixedFactoryInterface
{
    public function create(array $args = []): FormFieldControllerInterface;
}
