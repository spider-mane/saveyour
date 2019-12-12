<?php

namespace WebTheory\Saveyour\Fields;

use WebTheory\Saveyour\Contracts\FormFieldInterface;

class Radio extends AbstractCheckableInput implements FormFieldInterface
{
    /**
     *
     */
    protected $type = 'radio';
}
