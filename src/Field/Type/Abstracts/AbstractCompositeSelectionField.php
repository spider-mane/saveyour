<?php

namespace WebTheory\Saveyour\Field\Type\Abstracts;

use WebTheory\Saveyour\Contracts\Field\FormFieldInterface;
use WebTheory\Saveyour\Field\Abstracts\CompositeSelectionFieldTrait;
use WebTheory\Saveyour\Field\Abstracts\LabelMakerTrait;
use WebTheory\Saveyour\Field\Element\Label;

abstract class AbstractCompositeSelectionField extends AbstractFormField implements FormFieldInterface
{
    use LabelMakerTrait;
    use CompositeSelectionFieldTrait;

    protected array $labelOptions = [];

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

    protected function createSelectionLabel($selection): Label
    {
        return $this->createLabel($this->defineSelectionLabel($selection), $this->labelOptions);
    }
}
