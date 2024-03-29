<?php

namespace WebTheory\Saveyour\Field\Type;

use WebTheory\Saveyour\Contracts\Field\FormFieldInterface;
use WebTheory\Saveyour\Field\Type\Abstracts\AbstractInput;

class Hidden extends AbstractInput implements FormFieldInterface
{
    protected string $type = 'hidden';
}
