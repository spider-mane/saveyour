<?php

namespace WebTheory\Saveyour\Field\Type;

use WebTheory\Saveyour\Contracts\FormFieldInterface;

class Date extends AbstractInput implements FormFieldInterface
{
    protected string $type = 'date';
}
