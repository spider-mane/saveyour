<?php

namespace WebTheory\Saveyour\Fields;

use WebTheory\Saveyour\Concerns\LabelMaker;
use WebTheory\Saveyour\Contracts\FormFieldInterface;

abstract class AbstractCompositeField extends AbstractFormField implements FormFieldInterface
{
    use LabelMaker;

    /**
     * @var array
     */
    protected $labelOptions = [];

    /**
     * Get the value of labelOptions
     *
     * @return array
     */
    public function getLabelOptions(): array
    {
        return $this->labelOptions;
    }

    /**
     * Set the value of labelOptions
     *
     * @param array $labelOptions
     *
     * @return self
     */
    public function setLabelOptions(array $labelOptions)
    {
        $this->labelOptions = $labelOptions;

        return $this;
    }
}
