<?php

namespace WebTheory\Saveyour\Field\Type;

use WebTheory\Saveyour\Contracts\FormFieldInterface;

class DateTimeLocal extends AbstractInput implements FormFieldInterface
{
    protected string $type = 'datetime-local';
}
