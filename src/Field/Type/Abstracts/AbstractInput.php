<?php

namespace WebTheory\Saveyour\Field\Type\Abstracts;

use WebTheory\Html\AbstractHtmlElement;
use WebTheory\Saveyour\Contracts\FormFieldInterface;

abstract class AbstractInput extends AbstractStandardFormControl implements FormFieldInterface
{
    protected string $type = 'text';

    protected ?string $dataList = null;

    /**
     * Get the value of type
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    protected function resolveAttributes(): AbstractHtmlElement
    {
        return parent::resolveAttributes()
            ->addAttribute('type', $this->type)
            ->addAttribute('value', $this->value)
            ->addAttribute('datalist', $this->dataList);
    }

    protected function renderHtmlMarkup(): string
    {
        return $this->open('input', $this->attributes);
    }
}
