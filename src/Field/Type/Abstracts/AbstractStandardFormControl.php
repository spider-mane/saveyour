<?php

namespace WebTheory\Saveyour\Field\Type\Abstracts;

use WebTheory\Saveyour\Contracts\Field\FormFieldInterface;

abstract class AbstractStandardFormControl extends AbstractFormField implements FormFieldInterface
{
    /**
     * @return $this
     */
    protected function resolveAttributes(): AbstractStandardFormControl
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
