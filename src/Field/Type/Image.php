<?php

namespace WebTheory\Saveyour\Field\Type;

use WebTheory\Saveyour\Contracts\FormFieldInterface;

class Image extends AbstractInput implements FormFieldInterface
{
    protected string $type = 'image';
}
