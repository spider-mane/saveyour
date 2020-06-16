<?php

namespace WebTheory\Saveyour\Contracts;

interface FormFieldRepositoryInterface
{
    /**
     *
     */
    public function getField(string $field): ?FormFieldControllerInterface;
}
