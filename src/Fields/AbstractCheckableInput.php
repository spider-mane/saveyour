<?php

namespace WebTheory\Saveyour\Fields;

use WebTheory\Html\AbstractHtmlElement;
use WebTheory\Saveyour\Contracts\CheckableFieldInterface;
use WebTheory\Saveyour\Fields\AbstractInput;

abstract class AbstractCheckableInput extends AbstractInput implements CheckableFieldInterface
{
    /**
     * @var bool
     */
    protected $checked;

    /**
     * Get the value of checked
     *
     * @return bool
     */
    public function isChecked(): bool
    {
        return $this->checked;
    }

    /**
     * Set the value of checked
     *
     * @param bool $checked
     *
     * @return self
     */
    public function setChecked(bool $checked): CheckableFieldInterface
    {
        $this->checked = $checked;

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
