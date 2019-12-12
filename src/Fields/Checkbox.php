<?php

namespace WebTheory\Saveyour\Fields;

use WebTheory\Saveyour\Contracts\FormFieldInterface;

class Checkbox extends AbstractCheckableInput implements FormFieldInterface
{
    /**
     *
     */
    protected $type = 'checkbox';
}
