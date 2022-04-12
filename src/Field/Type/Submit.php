<?php

namespace WebTheory\Saveyour\Field\Type;

use WebTheory\Saveyour\Contracts\FormFieldInterface;
use WebTheory\Saveyour\Field\Type\Abstracts\AbstractInput;

class Submit extends AbstractInput implements FormFieldInterface
{
    protected string $type = 'submit';
}
