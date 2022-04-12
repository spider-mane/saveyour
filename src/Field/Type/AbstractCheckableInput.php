<?php

namespace WebTheory\Saveyour\Field\Type;

use WebTheory\Html\AbstractHtmlElement;
use WebTheory\Saveyour\Contracts\CheckableFieldInterface;

abstract class AbstractCheckableInput extends AbstractInput implements CheckableFieldInterface
{
    protected ?bool $checked = null;

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
