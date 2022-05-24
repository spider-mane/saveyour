<?php

namespace WebTheory\Saveyour\Field\Element;

use WebTheory\Saveyour\Field\Abstracts\AbstractValuableElement;
use WebTheory\Saveyour\Field\Abstracts\MultiValueSelectionTrait;
use WebTheory\Saveyour\Field\Abstracts\RendersOptionsTrait;

class OptGroup extends AbstractValuableElement
{
    use MultiValueSelectionTrait;
    use RendersOptionsTrait;

    protected $value = [];

    protected string $label;

    protected bool $disabled = false;

    public function __construct(string $label)
    {
        $this->label = $label;

        parent::__construct();
    }

    public function isDisabled(): bool
    {
        return $this->disabled;
    }

    /**
     * @return $this
     */
    public function setDisabled(bool $disabled): OptGroup
    {
        $this->disabled = $disabled;

        return $this;
    }

    /**
     * @return $this
     */
    protected function resolveAttributes(): OptGroup
    {
        parent::resolveAttributes()
            ->addAttribute('label', $this->label)
            ->addAttribute('disabled', $this->disabled);

        return $this;
    }

    protected function renderHtmlMarkup(): string
    {
        return $this->tag('optgroup', $this->attributes, $this->renderSelection());
    }
}
