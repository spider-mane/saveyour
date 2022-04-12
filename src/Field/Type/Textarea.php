<?php

namespace WebTheory\Saveyour\Field\Type;

use WebTheory\Html\AbstractHtmlElement;
use WebTheory\Saveyour\Contracts\FormFieldInterface;
use WebTheory\Saveyour\Field\Type\Abstracts\AbstractStandardFormControl;

class Textarea extends AbstractStandardFormControl implements FormFieldInterface
{
    public ?int $rows = null;

    /**
     * Get the value of rows
     *
     * @return int
     */
    public function getRows(): int
    {
        return $this->rows;
    }

    /**
     * Set the value of rows
     *
     * @param int $rows
     *
     * @return self
     */
    public function setRows(int $rows)
    {
        $this->rows = $rows;

        return $this;
    }

    protected function resolveAttributes(): AbstractHtmlElement
    {
        return parent::resolveAttributes()
            ->addAttribute('rows', $this->rows);
    }

    protected function renderHtmlMarkup(): string
    {
        return $this->tag('textarea', $this->attributes, $this->value);
    }
}
