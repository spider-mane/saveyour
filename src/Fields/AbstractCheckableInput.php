<?php

namespace WebTheory\Saveyour\Fields;

use WebTheory\Html\AbstractHtmlElement;
use WebTheory\Saveyour\Contracts\FormFieldInterface;
use WebTheory\Saveyour\Fields\AbstractInput;

abstract class AbstractCheckableInput extends AbstractInput implements FormFieldInterface
{

    /**
     * @var bool
     */
    protected $checked;

    /**
     * Get the value of selected
     *
     * @return bool
     */
    public function isChecked(): bool
    {
        return $this->checked;
    }

    /**
     * Set the value of selected
     *
     * @param bool $selected
     *
     * @return self
     */
    public function setChecked(bool $selected)
    {
        $this->checked = $selected;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    protected function resolveAttributes(): AbstractHtmlElement
    {
        return parent::resolveAttributes()
            ->addAttribute('checked', $this->checked);
    }
}
