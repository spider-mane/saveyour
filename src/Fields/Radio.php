<?php

namespace WebTheory\Saveyour\Fields;

use WebTheory\Saveyour\Contracts\CheckableFieldInterface;

class Radio extends AbstractCheckableInput implements CheckableFieldInterface
{
    /**
     *
     */
    protected $type = 'radio';
}
