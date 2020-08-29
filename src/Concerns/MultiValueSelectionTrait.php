<?php

namespace WebTheory\Saveyour\Concerns;

trait MultiValueSelectionTrait
{
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

    /**
     * Get value for hidden input that facilitates unsetting all values on the server
     *
     * @return string
     */
    public function getClearControl(): string
    {
        return $this->clearControl;
    }

    /**
     * Set value for hidden input that facilitates unsetting all values on the server
     *
     * @param string
     *
     * @return self
     */
    public function setClearControl(string $clearControl)
    {
        $this->clearControl = $clearControl;

        return $this;
    }
}
