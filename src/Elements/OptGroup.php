<?php

namespace WebTheory\Saveyour\Elements;

use WebTheory\Html\AbstractHtmlElement;
use WebTheory\Saveyour\Concerns\IsSimpleSelectionFieldTrait;
use WebTheory\Saveyour\Concerns\MultiValueSelectionTrait;

class OptGroup extends AbstractHtmlElement
{
    use IsSimpleSelectionFieldTrait;
    use MultiValueSelectionTrait;

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
        return $this->tag('optgroup', $this->renderSelection(), $this->attributes);
    }

    /**
     *
     */
    protected function renderSelection(): string
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
    protected function createOption($selection): Option
    {
        return new Option(
            $this->defineSelectionText($selection),
            $this->defineSelectionValue($selection)
        );
    }
}
