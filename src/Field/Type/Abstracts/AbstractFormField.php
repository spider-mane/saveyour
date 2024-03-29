<?php

namespace WebTheory\Saveyour\Field\Type\Abstracts;

use WebTheory\Saveyour\Contracts\Field\FormFieldInterface;
use WebTheory\Saveyour\Field\Abstracts\AbstractValuableElement;
use WebTheory\Saveyour\Field\Element\Label;

abstract class AbstractFormField extends AbstractValuableElement implements FormFieldInterface
{
    protected string $name = '';

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
     * @return $this
     */
    public function setName(string $name): AbstractFormField
    {
        $this->name = $name;

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
     * @return $this
     */
    public function setLabel(string $label): AbstractFormField
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
     * @return $this
     */
    public function setRequired(bool $required): AbstractFormField
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
     * @return $this
     */
    public function setDisabled(bool $disabled): AbstractFormField
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
     * @return $this
     */
    public function setReadOnly(bool $readonly): AbstractFormField
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
     * @return $this
     */
    public function setPlaceholder(string $placeholder): AbstractFormField
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
