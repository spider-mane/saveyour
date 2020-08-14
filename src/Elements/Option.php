<?php

namespace WebTheory\Saveyour\Elements;

use WebTheory\Html\AbstractHtmlElement;

class Option extends AbstractHtmlElement
{
    /**
     * @var string
     */
    protected $text;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var bool
     */
    protected $selected = false;

    /**
     * @var bool
     */
    protected $disabled = false;

    /**
     *
     */
    public function __construct(string $text, string $value)
    {
        $this->text = $text;
        $this->value = $value;

        parent::__construct();
    }

    /**
     * Get the value of text
     *
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Get the value of value
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Get the value of selected
     *
     * @return bool
     */
    public function isSelected(): bool
    {
        return $this->selected;
    }

    /**
     * Set the value of selected
     *
     * @param bool $selected
     *
     * @return self
     */
    public function setSelected(bool $selected)
    {
        $this->selected = $selected;

        return $this;
    }

    /**
     * Get the value of disabled
     *
     * @return bool
     */
    public function isDisabled(): bool
    {
        return $this->disabled;
    }

    /**
     * Set the value of disabled
     *
     * @param bool $disabled
     *
     * @return self
     */
    public function setDisabled(bool $disabled)
    {
        $this->disabled = $disabled;

        return $this;
    }

    /**
     *
     */
    protected function resolveAttributes(): AbstractHtmlElement
    {
        return parent::resolveAttributes()
            ->addAttribute('value', $this->value)
            ->addAttribute('selected', $this->selected)
            ->addAttribute('disabled', $this->disabled);
    }

    /**
     *
     */
    protected function renderHtmlMarkup(): string
    {
        return $this->tag('option', $this->attributes, $this->text);
    }
}
