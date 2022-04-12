<?php

namespace WebTheory\Saveyour\Field\Type;

use WebTheory\Saveyour\Contracts\FormFieldInterface;

class File extends AbstractInput implements FormFieldInterface
{
    protected string $type = 'file';
}
