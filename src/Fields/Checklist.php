<?php

namespace WebTheory\Saveyour\Fields;

use WebTheory\Saveyour\Concerns\MultiValueSelectionTrait;
use WebTheory\Saveyour\Contracts\FormFieldInterface;

class Checklist extends AbstractCompositeSelectionField implements FormFieldInterface
{
    use MultiValueSelectionTrait;

    /**
     * Value for hidden input that facilitates unsetting all values on the server
     *
     * @var string
     */
    protected $clearControl = '0';

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

    /**
     *
     */
    protected function renderHtmlMarkup(): string
    {
        return $this->open('div', $this->attributes)
            . $this->createClearControlField()->setValue($this->clearControl)
            . $this->open('ul')
            . $this->renderSelection()
            . $this->close('ul')
            . $this->close('div');
    }

    /**
     *
     */
    protected function createClearControlField(): Hidden
    {
        return (new Hidden())->setName($this->name . "[]");
    }

    /**
     *
     */
    protected function renderSelection(): string
    {
        $html = '';

        foreach ($this->getSelectionData() as $selection) {
            $id  = $this->defineSelectionId($selection);
            $value = $this->defineSelectionValue($selection);

            $checked = $this->isSelectionSelected($value);

            $html .= $this->open('li')
                . $this->createSelectionCheckbox($selection)->setChecked($checked)
                . $this->createSelectionLabel($selection)->setFor($id)
                . $this->close('li');
        }

        return $html;
    }

    /**
     *
     */
    protected function createSelectionCheckbox($item): Checkbox
    {
        return (new Checkbox())
            ->setName($this->name . '[]')
            ->setId($this->defineSelectionId($item))
            ->setValue($this->defineSelectionValue($item));
    }
}
