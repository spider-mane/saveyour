<?php

namespace WebTheory\Saveyour\Field\Type;

use WebTheory\Saveyour\Contracts\CheckableFieldInterface;

class Checkbox extends AbstractCheckableInput implements CheckableFieldInterface
{
    protected string $type = 'checkbox';
}
