<?php

namespace WebTheory\Saveyour\Field\Element;

use WebTheory\Html\AbstractHtmlElement;
use WebTheory\Saveyour\Field\Abstracts\MultiValueSelectionTrait;
use WebTheory\Saveyour\Field\Abstracts\RendersOptionsTrait;

class OptGroup extends AbstractHtmlElement
{
    use MultiValueSelectionTrait;
    use RendersOptionsTrait;

    protected array $value = [];

    protected string $label;

    protected bool $disabled = false;

    public function __construct(string $label)
    {
        $this->label = $label;

        parent::__construct();
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
    public function setDisabled(bool $disabled): OptGroup
    {
        $this->disabled = $disabled;

        return $this;
    }

    protected function resolveAttributes(): AbstractHtmlElement
    {
        return parent::resolveAttributes()
            ->addAttribute('label', $this->label)
            ->addAttribute('disabled', $this->disabled);
    }

    protected function renderHtmlMarkup(): string
    {
        return $this->tag('optgroup', $this->attributes, $this->renderSelection());
    }
}
