<?php

namespace WebTheory\Saveyour\Field\Type;

use WebTheory\Html\AbstractHtmlElement;
use WebTheory\Saveyour\Contracts\FormFieldInterface;

class Csrf extends Hidden implements FormFieldInterface
{
    /**
     * @var string
     */
    protected $form;

    protected function resolveAttributes(): AbstractHtmlElement
    {
        return parent::resolveAttributes()
            ->addAttribute('form', $this->form);
    }
}
