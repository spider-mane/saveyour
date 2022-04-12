<?php

namespace WebTheory\Saveyour\Contracts\Field;

interface CheckableFieldInterface extends FormFieldInterface
{
    public function setChecked(bool $checked): CheckableFieldInterface;

    public function isChecked(): bool;
}
