<?php

namespace WebTheory\Saveyour\Concerns;

trait MultiValueSelectionTrait
{
    /**
     *
     */
    protected $value = [];

    /**
     *
     */
    protected function isSelectionSelected(string $value): bool
    {
        return in_array($value, $this->value);
    }

    /**
     * Set the value of value
     *
     * @param mixed $value
     *
     * @return self
     */
    public function setValue($value)
    {
        $this->value = (array) $value;

        return $this;
    }
}
