<?php

namespace WebTheory\Saveyour\Field\Abstracts;

use WebTheory\Saveyour\Field\Type\Abstracts\AbstractCompositeSelectionField;
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
    public function setValue($value): AbstractCompositeSelectionField
    {
        $this->value = (array) $value;

        return $this;
    }

    protected function createClearControlField(): Hidden
    {
        return new Hidden();
    }
}
