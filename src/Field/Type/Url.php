<?php

namespace WebTheory\Saveyour\Field\Type;

use WebTheory\Saveyour\Contracts\FormFieldInterface;

class Url extends AbstractInput implements FormFieldInterface
{
    protected string $type = 'url';
}
