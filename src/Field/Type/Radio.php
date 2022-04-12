<?php

namespace WebTheory\Saveyour\Field\Type;

use WebTheory\Saveyour\Contracts\Field\CheckableFieldInterface;
use WebTheory\Saveyour\Field\Type\Abstracts\AbstractCheckableInput;

class Radio extends AbstractCheckableInput implements CheckableFieldInterface
{
    protected string $type = 'radio';
}
