<?php

namespace WebTheory\Saveyour\Field\Type;

use WebTheory\Saveyour\Contracts\FormFieldInterface;

class Button extends AbstractInput implements FormFieldInterface
{
    protected string $type = 'button';
}
