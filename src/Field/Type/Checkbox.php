<?php

namespace WebTheory\Saveyour\Field\Type;

use WebTheory\Saveyour\Contracts\CheckableFieldInterface;
use WebTheory\Saveyour\Field\Type\Abstracts\AbstractCheckableInput;

class Checkbox extends AbstractCheckableInput implements CheckableFieldInterface
{
    protected string $type = 'checkbox';
}
