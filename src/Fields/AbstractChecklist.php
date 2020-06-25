<?php

namespace WebTheory\Saveyour\Fields;

use WebTheory\Saveyour\Concerns\HasLabelsTrait;
use WebTheory\Saveyour\Concerns\IsSelectionFieldTrait;
use WebTheory\Saveyour\Contracts\FormFieldInterface;
use WebTheory\Saveyour\Elements\Label;

abstract class AbstractChecklist extends AbstractFormField implements FormFieldInterface
{
    use HasLabelsTrait;
    use IsSelectionFieldTrait;

    /**
     * Associative array of item definitions with the value as the key
     *
     * @var array
     */
    protected $items = [];

    /**
     * @var string[]
     */
    protected $value = [];

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
    protected function isItemChecked(string $item): bool
    {
        return in_array($item, $this->value);
    }

    /**
     *
     */
    protected function createItemLabel(array $values): Label
    {
        return $this->createLabel($values['label'] ?? '', $this->labelOptions);
    }
}
