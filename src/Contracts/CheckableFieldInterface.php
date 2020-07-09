<?php

namespace WebTheory\Saveyour\Contracts;

interface CheckableFieldInterface extends FormFieldInterface
{
    /**
     *
     */
    public function setChecked(bool $checked): CheckableFieldInterface;

    /**
     *
     */
    public function isChecked(): bool;
}
