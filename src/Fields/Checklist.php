<?php

namespace WebTheory\Saveyour\Fields;

use WebTheory\Saveyour\Concerns\MultiValueSelectionTrait;
use WebTheory\Saveyour\Contracts\ChecklistItemsProviderInterface;
use WebTheory\Saveyour\Contracts\FormFieldInterface;

class Checklist extends AbstractCompositeSelectionField implements FormFieldInterface
{
    use MultiValueSelectionTrait;

    /**
     * @var array
     */
    protected $value = [];

    /**
     * Value for hidden input that facilitates removing all values on the server
     * if no values are selected in the form.
     *
     * @var string
     */
    protected $clearControl = '0';

    /**
     * @var ChecklistItemsProviderInterface
     */
    protected $selectionProvider;

    /**
     * Get the value of selectionProvider
     *
     * @return ChecklistItemsProviderInterface
     */
    public function getSelectionProvider(): ChecklistItemsProviderInterface
    {
        return $this->selectionProvider;
    }

    /**
     * Set the value of selectionProvider
     *
     * @param ChecklistItemsProviderInterface $selectionProvider
     *
     * @return self
     */
    public function setSelectionProvider(ChecklistItemsProviderInterface $selectionProvider)
    {
        $this->selectionProvider = $selectionProvider;

        return $this;
    }

    /**
     *
     */
    protected function getSelectionItemsName(): string
    {
        return $this->name . '[]';
    }

    /**
     *
     */
    protected function renderHtmlMarkup(): string
    {
        $clearControl = $this->createClearControlField()
            ->setName($this->getSelectionItemsName())
            ->setValue($this->clearControl);

        $list = $this->tag('ul', [], $this->renderSelection());

        return $this->tag('div', $this->attributes, $clearControl . $list);
    }

    /**
     *
     */
    protected function createClearControlField(): Hidden
    {
        return new Hidden();
    }

    /**
     *
     */
    protected function renderSelection(): string
    {
        $html = '';

        foreach ($this->getSelectionData() as $selection) {
            $id  = $this->defineSelectionId($selection);
            $value = $this->defineSelectionValue($selection);

            $checkbox = $this->createSelectionCheckbox($selection)
                ->setChecked($this->isSelectionSelected($value))
                ->setDisabled($this->disabled)
                ->setName($this->getSelectionItemsName())
                ->setValue($value)
                ->setId($id);

            $label = $this->createSelectionLabel($selection)->setFor($id);

            $html .= $this->tag('li', [], $checkbox . $label);
        }

        return $html;
    }

    /**
     *
     */
    protected function createSelectionCheckbox($selection): Checkbox
    {
        return new Checkbox();
    }
}
