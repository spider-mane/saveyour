<?php

namespace WebTheory\Saveyour\Fields;

use WebTheory\Saveyour\Contracts\CheckableFieldInterface;

class Checkbox extends AbstractCheckableInput implements CheckableFieldInterface
{
    protected $type = 'checkbox';
}
