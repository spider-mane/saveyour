<?php

namespace WebTheory\Saveyour\Elements;

use WebTheory\Html\AbstractHtmlElement;
use WebTheory\Saveyour\Concerns\MultiValueSelectionTrait;
use WebTheory\Saveyour\Concerns\RendersOptionsTrait;
use WebTheory\Saveyour\Contracts\OptionsProviderInterface;

class OptGroup extends AbstractHtmlElement
{
    use MultiValueSelectionTrait;
    use RendersOptionsTrait;

    /**
     *
     */
    protected $value = [];

    /**
     * @var string
     */
    protected $label;

    /**
     * @var bool
     */
    protected $disabled = false;

    /**
     * @var OptionsProviderInterface
     */
    protected $selectionProvider;

    /**
     *
     */
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
            ->addAttribute('label', $this->label)
            ->addAttribute('disabled', $this->disabled);
    }

    /**
     *
     */
    protected function renderHtmlMarkup(): string
    {
        return $this->tag('optgroup', $this->attributes, $this->renderSelection());
    }
}
