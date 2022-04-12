<?php

namespace WebTheory\Saveyour\Fields;

use WebTheory\Saveyour\Contracts\FormFieldInterface;

class Submit extends AbstractInput implements FormFieldInterface
{
    protected string $type = 'submit';
}
