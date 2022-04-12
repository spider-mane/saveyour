<?php

namespace WebTheory\Saveyour\Field\Type;

use WebTheory\Saveyour\Contracts\FormFieldInterface;

class Email extends AbstractInput implements FormFieldInterface
{
    protected string $type = 'email';
}
