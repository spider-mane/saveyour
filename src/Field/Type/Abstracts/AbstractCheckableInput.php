<?php

namespace WebTheory\Saveyour\Field\Type\Abstracts;

use WebTheory\Saveyour\Contracts\Field\CheckableFieldInterface;

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
     * @return $this
     */
    public function setChecked(bool $checked): CheckableFieldInterface
    {
        $this->checked = $checked;

        return $this;
    }

    /**
     * @return $this
     */
    protected function resolveAttributes(): AbstractCheckableInput
    {
        parent::resolveAttributes()
            ->addAttribute('checked', $this->checked);

        return $this;
    }
}
