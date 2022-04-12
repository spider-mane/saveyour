<?php

namespace WebTheory\Saveyour\Field\Type;

use WebTheory\Saveyour\Contracts\CheckableFieldInterface;

class Radio extends AbstractCheckableInput implements CheckableFieldInterface
{
    protected string $type = 'radio';
}
