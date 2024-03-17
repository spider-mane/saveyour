<?php

namespace WebTheory\Saveyour\Contracts\Factory;

use WebTheory\Factory\Interfaces\FlexFactoryInterface;
use WebTheory\Saveyour\Contracts\Controller\FormFieldControllerInterface;

interface FormFieldControllerFlexFactoryInterface extends FlexFactoryInterface
{
    public function create(string $controller, array $args = []): FormFieldControllerInterface;
}
