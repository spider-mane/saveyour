<?php

namespace WebTheory\Saveyour\Fields;

use WebTheory\Html\AbstractHtmlElement;
use WebTheory\Saveyour\Concerns\IsSelectionFieldTrait;
use WebTheory\Saveyour\Contracts\FormFieldInterface;

class Select extends AbstractStandardFormControl implements FormFieldInterface
{
    use IsSelectionFieldTrait;

    /**
     *
     */
    public $options = [];

    /**
     *
     */
    public $value = [];

    /**
     * @var bool
     */
    public $multiple = false;

    /**
     * @var int
     */
    public $size;

    /**
     * Get the value of options
     *
     * @return mixed
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Set the value of options
     *
     * @param mixed $options
     *
     * @return self
     */
    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Set the value of value
     *
     * @param mixed $value
     *
     * @return self
     */
    public function setValue($value)
    {
        $this->value[] = $value;

        return $this;
    }

    /**
     * Get the value of multiple
     *
     * @return bool
     */
    public function isMultiple(): bool
    {
        return $this->multiple;
    }

    /**
     * Set the value of multiple
     *
     * @param bool $multiple
     *
     * @return self
     */
    public function setMultiple(bool $multiple)
    {
        $this->multiple = $multiple;

        return $this;
    }

    /**
     *
     */
    protected function resolveAttributes(): AbstractHtmlElement
    {
        return parent::resolveAttributes()
            ->addAttribute('multiple', $this->multiple);
    }

    /**
     *
     */
    protected function getItemsToRender(): array
    {
        return $this->selectionProvider
            ? $this->selectionProvider->getSelection()
            : $this->options;
    }

    /**
     *
     */
    public function renderHtmlMarkup(): string
    {
        $html = '';

        $html .= $this->open('select', $this->attributes);

        if (!empty($this->placeholder)) {
            $html .= $this->open('option') . $this->placeholder . $this->close('option');
        }

        foreach ($this->getItemsToRender() as $value => $option) {
            $optionAttr = ['value' => $value];

            if (in_array($value, $this->value)) {
                $optionAttr['selected'] = true;
            }

            $html .= $this->open('option', $optionAttr);
            $html .= $option;
            $html .= $this->close('option');
        }

        $html .= $this->close('select');

        return $html;
    }
}
