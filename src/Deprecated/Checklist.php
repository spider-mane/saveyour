<?php

namespace WebTheory\Saveyour\Deprecated;

use WebTheory\Saveyour\Contracts\FormFieldInterface;
use WebTheory\Saveyour\Elements\Label;
use WebTheory\Saveyour\Fields\AbstractCompositeField;
use WebTheory\Saveyour\Fields\Checkbox;
use WebTheory\Saveyour\Fields\Hidden;

class Checklist extends AbstractCompositeField implements FormFieldInterface
{
    /**
     * Associative array of item definitions with the value as the key
     *
     * @var array
     */
    protected $items = [];

    /**
     *
     */
    protected $value = [];

    /**
     * @var array
     */
    protected $labelOptions = [];

    /**
     * Value for hidden input that facilitates unsetting all values on the server
     *
     * @var string
     */
    protected $clearControl = '0';

    /**
     * Value for hidden input that facilitates unsetting single value on the server
     *
     * @var mixed
     */
    protected $toggleControl = '0';

    /**
     * @var null|string
     */
    protected $controlType = 'clear';

    /**
     *
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Get the value of items
     *
     * @return mixed
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Set the value of items
     *
     * @param mixed $items
     *
     * @return self
     */
    public function setItems($items)
    {
        $this->items = $items;

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
        $this->controlType = 'clear';

        return $this;
    }

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
        $this->controlType = 'toggle';

        return $this;
    }

    /**
     *
     */
    public function isToggleControlType(): bool
    {
        return 'control' === $this->controlType;
    }

    /**
     *
     */
    public function isClearControlType(): bool
    {
        return 'clear' === $this->controlType;
    }

    /**
     *
     */
    public function getControlValue(): string
    {
        return $this->isClearControlType() ? $this->clearControl : $this->toggleControl;
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
    protected function defineItemName(array $values)
    {
        $basename = $values['name'] ?? '';

        return $this->isToggleControlType()
            ? $this->name . "[{$basename}]"
            : $this->name . "[]";
    }

    /**
     *
     */
    protected function createItemCheckBox(array $values): Checkbox
    {
        return (new Checkbox())
            ->setId($values['id'] ?? '')
            ->setValue($values['value'] ?? '')
            ->setName($this->defineItemName($values));
    }

    /**
     *
     */
    protected function isItemChecked(string $item): bool
    {
        return in_array($item, $this->value);
    }

    /**
     *
     */
    protected function createItemLabel(array $values): Label
    {
        return $this->createLabel($values['label'] ?? '', $this->labelOptions);
    }

    /**
     *
     */
    protected function defineChecklistItem(string $item, array $values)
    {
        $html = '';
        $html .= $this->isToggleControlType() ? $this->createItemToggleControl($values) : '';
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
        $html .= $this->isClearControlType() ? $this->createClearControl() : '';
        $html .= $this->open('ul');

        foreach ($this->items as $item => $values) {
            $html .= $this->open('li');
            $html .= $this->defineChecklistItem($item, $values);
            $html .= $this->close('li');
        }

        $html .= $this->close('ul');
        $html .= $this->close('div');

        return $html;
    }
}
