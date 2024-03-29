<?php

namespace WebTheory\Saveyour\Field\Type;

use WebTheory\Saveyour\Contracts\Field\FormFieldInterface;
use WebTheory\Saveyour\Field\Abstracts\MultiValueSelectionTrait;
use WebTheory\Saveyour\Field\Abstracts\RendersOptionsTrait;
use WebTheory\Saveyour\Field\Element\OptGroup;
use WebTheory\Saveyour\Field\Element\Option;
use WebTheory\Saveyour\Field\Type\Abstracts\AbstractStandardFormControl;

class Select extends AbstractStandardFormControl implements FormFieldInterface
{
    use MultiValueSelectionTrait;
    use RendersOptionsTrait {
        renderSelection as renderSelectionFromProvider;
    }

    protected $value = [];

    /**
     * @var OptGroup[]
     */
    protected array $groups = [];

    protected bool $multiple = false;

    protected ?int $size = null;

    public function getGroups()
    {
        return $this->groups;
    }

    public function setGroups(OptGroup ...$optGroups)
    {
        $this->groups = $optGroups;
    }

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
     * @return $this
     */
    public function setMultiple(bool $multiple): Select
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
     * @return $this
     */
    public function setSize(int $size): Select
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return $this
     */
    protected function resolveAttributes(): Select
    {
        parent::resolveAttributes()
            ->addAttribute('name', $this->resolveNameAttribute())
            ->addAttribute('multiple', $this->multiple)
            ->addAttribute('size', $this->size);

        return $this;
    }

    protected function resolveNameAttribute()
    {
        return $this->name . ($this->multiple ? '[]' : '');
    }

    protected function renderHtmlMarkup(): string
    {
        $html = '';

        if ($this->multiple) {
            $html .= $this->createClearControlField()
                ->setName($this->resolveNameAttribute())
                ->setValue('');
        }

        $html .= $this->tag('select', $this->attributes, $this->renderOptions());

        return $html;
    }

    protected function renderOptions()
    {
        $html = '';

        if (!empty($this->placeholder)) {
            $html .= $this->createPlaceholder();
        }

        $html .= $this->renderSelection();

        return $html;
    }

    protected function createPlaceholder(): Option
    {
        return new Option($this->placeholder, '');
    }

    protected function renderSelection(): string
    {
        return empty($this->groups)
            ? $this->renderSelectionFromProvider()
            : $this->renderSelectionFromOptGroups();
    }

    protected function renderSelectionFromOptGroups(): string
    {
        $html = '';

        foreach ($this->groups as $optgroup) {
            $html .= $optgroup->setValue($this->value);
        }

        return $html;
    }
}
