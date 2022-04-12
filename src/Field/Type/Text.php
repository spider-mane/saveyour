<?php

namespace WebTheory\Saveyour\Field\Type;

use WebTheory\Html\AbstractHtmlElement;
use WebTheory\Saveyour\Contracts\Field\FormFieldInterface;
use WebTheory\Saveyour\Field\Type\Abstracts\AbstractInput;

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
