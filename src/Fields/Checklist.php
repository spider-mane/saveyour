<?php

namespace WebTheory\Saveyour\Fields;

use WebTheory\Saveyour\Concerns\IsBasicallyChecklistTrait;
use WebTheory\Saveyour\Contracts\CheckableFieldInterface;
use WebTheory\Saveyour\Contracts\FormFieldInterface;

class Checklist extends AbstractChecklist implements FormFieldInterface
{
    use IsBasicallyChecklistTrait;

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
    protected function createClearControl(): Hidden
    {
        return (new Hidden())
            ->setName($this->name . "[]")
            ->setValue($this->clearControl);
    }

    /**
     *
     */
    protected function createItemCheckBox(array $values): CheckableFieldInterface
    {
        return (new Checkbox())
            ->setId($values['id'] ?? '')
            ->setValue($values['value'] ?? '')
            ->setName($this->name . '[]');
    }

    /**
     *
     */
    protected function defineChecklistItem(string $item, array $values)
    {
        $checkbox = $this->createItemCheckBox($values)->setChecked($this->isItemChecked($item));
        $label = $this->createItemLabel($values)->setFor($values['id'] ?? '');

        return $checkbox . $label;
    }

    /**
     *
     */
    protected function renderItemsFromProvider(): string
    {
        $html = '';
        $provider = $this->checklistItemProvider;

        foreach ($provider->provideItemsAsRawData() as $item) {
            $values = [
                'id' => $provider->provideItemId($item),
                'value' => $provider->provideItemValue($item),
                'label' => $provider->provideItemLabel($item)
            ];

            $checked = $this->isItemChecked($values['value']);

            $html .= $this->open('li')
                . $this->createItemCheckBox($values)->setChecked($checked)
                . $this->createItemLabel($values)->setFor($values['id'])
                . $this->close('li');
        }

        return $html;
    }

    /**
     *
     */
    protected function renderItemsFromSelection(): string
    {
        $html = '';

        foreach ($this->getItemsToRender() as $item => $values) {
            $html .= $this->open('li')
                . $this->defineChecklistItem($item, $values)
                . $this->close('li');
        }

        return $html;
    }

    /**
     *
     */
    protected function renderItems()
    {
        return isset($this->checklistItemProvider)
            ? $this->renderItemsFromProvider()
            : $this->renderItemsFromSelection();
    }

    /**
     *
     */
    public function renderHtmlMarkup(): string
    {
        return $this->open('div', $this->attributes ?? null)
            . $this->createClearControl()
            . $this->open('ul')
            . $this->renderItems()
            . $this->close('ul')
            . $this->close('div');
    }
}
