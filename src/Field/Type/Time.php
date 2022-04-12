<?php

namespace WebTheory\Saveyour\Field\Type;

use WebTheory\Saveyour\Contracts\FormFieldInterface;

class Time extends AbstractInput implements FormFieldInterface
{
    protected string $type = 'time';
}
