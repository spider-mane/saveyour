<?php

namespace WebTheory\Saveyour\Field\Type;

use WebTheory\Saveyour\Contracts\FormFieldInterface;

class Hidden extends AbstractInput implements FormFieldInterface
{
    protected string $type = 'hidden';
}
