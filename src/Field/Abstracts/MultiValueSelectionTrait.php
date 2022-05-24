<?php

namespace WebTheory\Saveyour\Field\Abstracts;

use WebTheory\Saveyour\Field\Type\Hidden;

trait MultiValueSelectionTrait
{
    protected function isSelectionSelected(string $value): bool
    {
        return in_array($value, $this->value);
    }

    /**
     * @return $this
     */
    public function setValue($value): AbstractValuableElement
    {
        $this->value = (array) $value;

        return $this;
    }

    protected function createClearControlField(): Hidden
    {
        return new Hidden();
    }
}
