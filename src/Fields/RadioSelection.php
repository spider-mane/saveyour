<?php

namespace WebTheory\Saveyour\Fields;

use WebTheory\Saveyour\Contracts\FormFieldInterface;

class RadioSelection extends AbstractCompositeField implements FormFieldInterface
{
    /**
     *
     */
    protected $items = [];

    /**
     * @var bool
     */
    protected $isInline = false;

    /**
     *
     */
    public function __construct(array $items)
    {
        $this->items = $items;
        parent::__construct();
    }

    /**
     * Get the value of isInline
     *
     * @return bool
     */
    public function getIsInline(): bool
    {
        return $this->isInline;
    }

    /**
     * Set the value of isInline
     *
     * @param bool $isInline
     *
     * @return self
     */
    public function setIsInline(bool $isInline)
    {
        $this->isInline = $isInline;

        return $this;
    }

    /**
     *
     */
    public function toHtml(): string
    {
        $html = '';

        foreach ($this->items as $item => $label) {

            $radio = (new Radio())
                ->setChecked($this->value !== $item ? false : true)
                ->setValue($item)
                ->setName($this->name)
                ->setClasslist(explode(' ', $this->classlist->parse()));

            $html .= $this->createLabel($radio . $label, $this->labelOptions);

            $html .= !$this->isInline ? '<br>' : '';
        }

        return $html;
    }
}
