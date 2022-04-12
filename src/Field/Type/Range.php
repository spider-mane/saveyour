<?php

namespace WebTheory\Saveyour\Field\Type;

use WebTheory\Saveyour\Contracts\FormFieldInterface;

class Range extends AbstractInput implements FormFieldInterface
{
    protected string $type = 'range';
}
