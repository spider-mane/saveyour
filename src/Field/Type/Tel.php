<?php

namespace WebTheory\Saveyour\Field\Type;

use WebTheory\Saveyour\Contracts\FormFieldInterface;

class Tel extends AbstractInput implements FormFieldInterface
{
    protected string $type = 'tel';
}
