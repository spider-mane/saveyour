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
     * Value for hidden input that facilitates unsetting all values on the server
     *
     * @var string
     */
    protected $clearControl = '0';

    /**
     * @var ChecklistItemsProviderInterface
     */
    protected $selectionProvider;

    /**
     * Get value for hidden input that facilitates unsetting all values on the server
     *
     * @return string
     */
    public function getClearControl(): string
    {
        return $this->clearControl;
    }

    /**
     * Set value for hidden input that facilitates unsetting all values on the server
     *
     * @param string
     *
     * @return self
     */
    public function setClearControl(string $clearControl)
    {
        $this->clearControl = $clearControl;

        return $this;
    }

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
