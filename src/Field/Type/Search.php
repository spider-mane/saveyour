<?php

namespace WebTheory\Saveyour\Field\Type;

use WebTheory\Saveyour\Contracts\FormFieldInterface;

class Search extends AbstractInput implements FormFieldInterface
{
    protected string $type = 'search';
}
