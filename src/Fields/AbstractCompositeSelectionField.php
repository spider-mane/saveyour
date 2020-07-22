<?php

namespace WebTheory\Saveyour\Fields;

use WebTheory\Saveyour\Concerns\IsCompositeSelectionFieldTrait;
use WebTheory\Saveyour\Concerns\LabelMaker;
use WebTheory\Saveyour\Contracts\FormFieldInterface;
use WebTheory\Saveyour\Elements\Label;

abstract class AbstractCompositeSelectionField extends AbstractFormField implements FormFieldInterface
{
    use LabelMaker;
    use IsCompositeSelectionFieldTrait;

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

    /**
     *
     */
    protected function createSelectionLabel($selection): Label
    {
        return $this->createLabel($this->defineSelectionLabel($selection), $this->labelOptions);
    }
}
