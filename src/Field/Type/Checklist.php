<?php

namespace WebTheory\Saveyour\Field\Type;

use WebTheory\Saveyour\Contracts\Field\FormFieldInterface;
use WebTheory\Saveyour\Contracts\Field\Selection\ChecklistItemsProviderInterface;
use WebTheory\Saveyour\Field\Abstracts\MultiValueSelectionTrait;
use WebTheory\Saveyour\Field\Type\Abstracts\AbstractCompositeSelectionField;

class Checklist extends AbstractCompositeSelectionField implements FormFieldInterface
{
    use MultiValueSelectionTrait;

    /**
     * @var array
     */
    protected $value = [];

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
     * @return $this
     */
    public function setSelectionProvider(ChecklistItemsProviderInterface $selectionProvider): Checklist
    {
        $this->selectionProvider = $selectionProvider;

        return $this;
    }

    protected function getSelectionItemsName(): string
    {
        return $this->name . '[]';
    }

    protected function renderHtmlMarkup(): string
    {
        $clearControl = $this->createClearControlField()
            ->setName($this->getSelectionItemsName())
            ->setValue('');

        $list = $this->tag('ul', [], $this->renderSelection());

        return $this->tag('div', $this->attributes, $clearControl . $list);
    }

    protected function renderSelection(): string
    {
        $html = '';

        foreach ($this->getSelectionData() as $selection) {
            $id = $this->defineSelectionId($selection);
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

    protected function createSelectionCheckbox($selection): Checkbox
    {
        return new Checkbox();
    }
}
