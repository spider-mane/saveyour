<?php

namespace WebTheory\Saveyour\Field\Type;

use WebTheory\Saveyour\Contracts\FormFieldInterface;

class Number extends AbstractInput implements FormFieldInterface
{
    protected string $type = 'number';
}
