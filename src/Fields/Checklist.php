<?php

namespace WebTheory\Saveyour\Fields;

use WebTheory\Saveyour\Contracts\FormFieldInterface;

class Checklist extends AbstractChecklist implements FormFieldInterface
{
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
    protected function createClearControl(): Hidden
    {
        return (new Hidden())
            ->setName($this->name . "[]")
            ->setValue($this->clearControl);
    }

    /**
     *
     */
    protected function createItemCheckBox(array $values): Checkbox
    {
        return (new Checkbox())
            ->setId($values['id'] ?? '')
            ->setValue($values['value'] ?? '')
            ->setName($this->name . '[]');
    }

    /**
     *
     */
    protected function defineChecklistItem(string $item, array $values)
    {
        $html = '';
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
        $html .= $this->createClearControl();
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
