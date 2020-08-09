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
     * @var ChecklistItemsProviderInterface
     */
    protected $selectionProvider;

    /**
     * Value for hidden input that facilitates unsetting all values on the server
     *
     * @var string
     */
    protected $clearControl = '0';

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
     *
     */
    protected function getSelectionItemsName()
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

        $list = $this->tag('ul', $this->renderSelection());

        return $this->tag('div', $clearControl . $list, $this->attributes);
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
                ->setName($this->getSelectionItemsName())
                ->setValue($value)
                ->setId($id);

            $label = $this->createSelectionLabel($selection)->setFor($id);

            $html .= $this->tag('li', $checkbox . $label);
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
