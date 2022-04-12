<?php

namespace WebTheory\Saveyour\Fields;

use WebTheory\Saveyour\Contracts\FormFieldInterface;

class Button extends AbstractInput implements FormFieldInterface
{
    protected string $type = 'button';
}
