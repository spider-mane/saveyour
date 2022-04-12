<?php

namespace WebTheory\Saveyour\Field\Type\Abstracts;

use WebTheory\Html\AbstractHtmlElement;
use WebTheory\Saveyour\Contracts\Field\FormFieldInterface;
use WebTheory\Saveyour\Field\Element\Label;

abstract class AbstractFormField extends AbstractHtmlElement implements FormFieldInterface
{
    protected string $name = '';

    /**
     * @var mixed
     */
    protected $value;

    protected string $label = '';

    protected ?string $form = null;

    protected string $placeholder = '';

    protected bool $required = false;

    protected bool $disabled = false;

    protected bool $readonly = false;

    /**
     * Get the value of name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @param string  $name
     *
     * @return self
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
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
     * Set the value of value
     *
     * @param mixed  $value
     *
     * @return self
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get the value of label
     *
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * Set the value of label
     *
     * @param string $label
     *
     * @return self
     */
    public function setLabel(string $label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get the value of required
     *
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * Set the value of required
     *
     * @param bool $required
     *
     * @return self
     */
    public function setRequired(bool $required)
    {
        $this->required = $required;

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
     * Get the value of readOnly
     *
     * @return bool
     */
    public function isReadOnly(): bool
    {
        return $this->readonly;
    }

    /**
     * Set the value of readOnly
     *
     * @param bool $readonly
     *
     * @return self
     */
    public function setReadOnly(bool $readonly)
    {
        $this->readonly = $readonly;

        return $this;
    }

    /**
     * Get the value of placeholder
     *
     * @return string
     */
    public function getPlaceholder(): string
    {
        return $this->placeholder;
    }

    /**
     * Set the value of placeholder
     *
     * @param string $placeholder
     *
     * @return self
     */
    public function setPlaceholder(string $placeholder)
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabelHtml(): string
    {
        return (new Label($this->label))->setFor($this->id)->toHtml();
    }
}
