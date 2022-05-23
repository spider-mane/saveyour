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

    public function getLabelOptions(): array
    {
        return $this->labelOptions;
    }

    /**
     * @return $this
     */
    public function setLabelOptions(array $labelOptions): AbstractCompositeSelectionField
    {
        $this->labelOptions = $labelOptions;

        return $this;
    }

    protected function createSelectionLabel($selection): Label
    {
        return $this->createLabel($this->defineSelectionLabel($selection), $this->labelOptions);
    }
}
