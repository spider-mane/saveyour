<?php

namespace WebTheory\Saveyour\Fields;

use WebTheory\Html\AbstractHtmlElement;
use WebTheory\Saveyour\Contracts\FormFieldInterface;

class Text extends AbstractInput implements FormFieldInterface
{
    protected string $type = 'text';

    protected ?string $pattern = null;

    protected function resolveAttributes(): AbstractHtmlElement
    {
        return parent::resolveAttributes()
            ->addAttribute('pattern', $this->pattern);
    }
}
