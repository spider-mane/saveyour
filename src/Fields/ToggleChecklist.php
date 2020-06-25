<?php

namespace WebTheory\Saveyour\Fields;

use WebTheory\Saveyour\Contracts\FormFieldInterface;

class ToggleChecklist extends AbstractChecklist implements FormFieldInterface
{
    /**
     * Value for hidden input that facilitates unsetting single value on the server
     *
     * @var mixed
     */
    protected $toggleControl = '0';

    /**
     * Get value for hidden input that facilitates unsetting single value on the server
     *
     * @return mixed
     */
    public function getToggleControl()
    {
        return $this->toggleControl;
    }

    /**
     * Set value for hidden input that facilitates unsetting single value on the server
     *
     * @param mixed $toggleControl
     *
     * @return self
     */
    public function setToggleControl($toggleControl)
    {
        $this->toggleControl = $toggleControl;

        return $this;
    }

    /**
     *
     */
    protected function createItemToggleControl(array $values): Hidden
    {
        $basename = $values['name'] ?? '';

        return (new Hidden())
            ->setName($this->name . "[{$basename}]")
            ->setValue($this->toggleControl);
    }

    /**
     *
     */
    protected function createItemCheckBox(array $values): Checkbox
    {
        $basename = $values['name'];

        return (new Checkbox())
            ->setId($values['id'] ?? '')
            ->setValue($values['value'] ?? '')
            ->setName($this->name . "[{$basename}]");
    }

    /**
     *
     */
    protected function defineChecklistItem(string $item, array $values)
    {
        $html = '';
        $html .= $this->createItemToggleControl($values);
        $html .= $this->createItemCheckBox($values)->setChecked($this->isItemChecked($item));
        $html .= $this->createItemLabel($values)->setFor($values['id'] ?? '');

        return $html;
    }

    /**
     *
     */
    public function renderHtmlMarkup(): string
    {
        $html = '';
        $html .= $this->open('div', $this->attributes ?? null);
        $html .= $this->open('ul');

        foreach ($this->getItemsToRender() as $item => $values) {
            $html .= $this->open('li');
            $html .= $this->defineChecklistItem($item, $values);
            $html .= $this->close('li');
        }

        $html .= $this->close('ul');
        $html .= $this->close('div');

        return $html;
    }
}
