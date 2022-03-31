<?php

namespace WebTheory\Saveyour\Fields;

use WebTheory\Html\AbstractHtmlElement;
use WebTheory\Saveyour\Contracts\FormFieldInterface;

class Text extends AbstractInput implements FormFieldInterface
{
    protected $type = 'text';

    /**
     * @var string
     */
    protected $pattern;

    protected function resolveAttributes(): AbstractHtmlElement
    {
        return parent::resolveAttributes()
            ->addAttribute('pattern', $this->pattern);
    }
}
