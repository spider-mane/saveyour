<?php

namespace WebTheory\Saveyour\Field\Type;

use WebTheory\Saveyour\Contracts\FormFieldInterface;

class Submit extends AbstractInput implements FormFieldInterface
{
    protected string $type = 'submit';
}
