<?php

namespace WebTheory\Saveyour\Fields;

use WebTheory\Html\AbstractHtmlElement;
use WebTheory\Saveyour\Concerns\IsSimpleSelectionFieldTrait;
use WebTheory\Saveyour\Concerns\MultiValueSelectionTrait;
use WebTheory\Saveyour\Contracts\FormFieldInterface;
use WebTheory\Saveyour\Elements\Option;

class Select extends AbstractStandardFormControl implements FormFieldInterface
{
    use IsSimpleSelectionFieldTrait;
    use MultiValueSelectionTrait;

    /**
     *
     */
    public $options = [];

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
    protected function createOption($selection): Option
    {
        return new Option(
            $this->defineSelectionText($selection),
            $this->defineSelectionValue($selection)
        );
    }

    /**
     *
     */
    protected function createPlaceholder(): Option
    {
        return new Option($this->placeholder, '');
    }

    /**
     *
     */
    protected function renderSelection()
    {
        $html = '';

        foreach ($this->getSelectionData() as $selection) {
            $selected = $this->isSelectionSelected($this->defineSelectionValue($selection));

            $html .= $this->createOption($selection)->setSelected($selected);
        }

        return $html;
    }

    /**
     *
     */
    protected function renderHtmlMarkup(): string
    {
        $html = '';
        $html .= $this->open('select', $this->attributes);

        if (!empty($this->placeholder)) {
            $html .= $this->createPlaceholder()->toHtml();
        }

        $html .= $this->renderSelection() . $this->close('select');

        return $html;
    }
}
