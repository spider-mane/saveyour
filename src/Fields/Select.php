<?php

namespace WebTheory\Saveyour\Fields;

use WebTheory\Html\AbstractHtmlElement;
use WebTheory\Saveyour\Concerns\MultiValueSelectionTrait;
use WebTheory\Saveyour\Concerns\RendersOptionsTrait;
use WebTheory\Saveyour\Contracts\FormFieldInterface;
use WebTheory\Saveyour\Elements\OptGroup;
use WebTheory\Saveyour\Elements\Option;

class Select extends AbstractStandardFormControl implements FormFieldInterface
{
    use MultiValueSelectionTrait;
    use RendersOptionsTrait {
        renderSelection as renderSelectionFromProvider;
    }

    /**
     *
     */
    protected $value = [];

    /**
     * @var OptGroup[]
     */
    protected $groups = [];

    /**
     * @var bool
     */
    protected $multiple = false;

    /**
     * @var int
     */
    protected $size;

    /**
     *
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     *
     */
    public function setGroups(OptGroup ...$optGroups)
    {
        $this->groups = $optGroups;
    }

    /**
     *
     */
    public function addGroup(OptGroup $optGroup)
    {
        $this->groups[] = $optGroup;
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
     * Get the value of size
     *
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * Set the value of size
     *
     * @param int $size
     *
     * @return self
     */
    public function setSize(int $size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     *
     */
    protected function resolveAttributes(): AbstractHtmlElement
    {
        return parent::resolveAttributes()
            ->addAttribute('name', $this->name . ($this->multiple ? '[]' : ''))
            ->addAttribute('multiple', $this->multiple)
            ->addAttribute('size', $this->size);
    }

    /**
     *
     */
    protected function renderHtmlMarkup(): string
    {
        return $this->tag('select', $this->attributes, $this->renderOptions());
    }

    /**
     *
     */
    protected function renderOptions()
    {
        $html = '';

        if (!empty($this->placeholder)) {
            $html .= $this->createPlaceholder();
        }

        $html .= $this->renderSelection();

        return $html;
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
    protected function renderSelection(): string
    {
        return empty($this->groups)
            ? $this->renderSelectionFromProvider()
            : $this->renderSelectionFromOptGroups();
    }

    /**
     *
     */
    protected function renderSelectionFromOptGroups(): string
    {
        $html = '';

        foreach ($this->groups as $optgroup) {
            $html .= $optgroup->setValue($this->value);
        }

        return $html;
    }
}
