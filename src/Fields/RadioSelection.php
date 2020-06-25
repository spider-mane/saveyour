<?php

namespace WebTheory\Saveyour\Fields;

use WebTheory\Saveyour\Concerns\HasLabelsTrait;
use WebTheory\Saveyour\Concerns\IsSelectionFieldTrait;
use WebTheory\Saveyour\Contracts\FormFieldInterface;

class RadioSelection extends AbstractFormField implements FormFieldInterface
{
    use HasLabelsTrait;
    use IsSelectionFieldTrait;

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
     * Get the value of items
     *
     * @return mixed
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Set the value of items
     *
     * @param mixed $items
     *
     * @return self
     */
    public function setItems($items)
    {
        $this->items = $items;

        return $this;
    }

    /**
     * Get the value of isInline
     *
     * @return bool
     */
    public function isInline(): bool
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
    protected function getItemsToRender(): array
    {
        return $this->selectionProvider
            ? $this->selectionProvider->getSelection()
            : $this->items;
    }

    /**
     *
     */
    protected function isItemSelected(string $item)
    {
        return $item === $this->value;
    }

    /**
     *
     */
    public function renderHtmlMarkup(): string
    {
        $html = '';

        foreach ($this->getItemsToRender() as $item => $label) {

            $radio = (new Radio())
                ->setChecked($this->isItemSelected($item))
                ->setValue($item)
                ->setName($this->name)
                ->setClasslist(explode(' ', $this->classlist->parse()));

            $html .= $this->createLabel($radio . $label, $this->labelOptions);

            $html .= !$this->isInline ? '<br>' : '';
        }

        return $html;
    }
}
