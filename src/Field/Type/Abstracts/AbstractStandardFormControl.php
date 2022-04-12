<?php

namespace WebTheory\Saveyour\Field\Type\Abstracts;

use WebTheory\Html\AbstractHtmlElement;
use WebTheory\Saveyour\Contracts\Field\FormFieldInterface;

abstract class AbstractStandardFormControl extends AbstractFormField implements FormFieldInterface
{
    /**
     * {@inheritDoc}
     */
    protected function resolveAttributes(): AbstractHtmlElement
    {
        return parent::resolveAttributes()
            ->addAttribute('name', $this->name)
            ->addAttribute('form', $this->form)
            ->addAttribute('disabled', $this->disabled)
            ->addAttribute('readonly', $this->readonly)
            ->addAttribute('required', $this->required)
            ->addAttribute('placeholder', $this->placeholder);
    }
}
