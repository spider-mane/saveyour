<?php

namespace WebTheory\Saveyour\Fields;

use WebTheory\Saveyour\Concerns\SingleValueSelectionTrait;
use WebTheory\Saveyour\Contracts\FormFieldInterface;
use WebTheory\Saveyour\Contracts\RadioGroupSelectionInterface;

class RadioGroup extends AbstractCompositeSelectionField implements FormFieldInterface
{
    use SingleValueSelectionTrait;

    /**
     * @var bool
     */
    protected $isInline = true;

    /**
     * @var RadioGroupSelectionInterface
     */
    protected $selectionProvider;

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
     * Get the value of selectionProvider
     *
     * @return RadioGroupSelectionInterface
     */
    public function getSelectionProvider(): RadioGroupSelectionInterface
    {
        return $this->selectionProvider;
    }

    /**
     * Set the value of selectionProvider
     *
     * @param RadioGroupSelectionInterface $selectionProvider
     *
     * @return self
     */
    public function setSelectionProvider(RadioGroupSelectionInterface $selectionProvider)
    {
        $this->selectionProvider = $selectionProvider;

        return $this;
    }

    /**
     *
     */
    protected function renderHtmlMarkup(): string
    {
        $html = '';

        foreach ($this->getSelectionData() as $selection) {
            $id = $this->defineSelectionId($selection);
            $value = $this->defineSelectionValue($selection);

            $radio = $this->createSelectionRadio($selection)
                ->setChecked($this->isSelectionSelected($value))
                ->setName($this->name)
                ->setValue($value)
                ->setId($id);

            $label = $this->createSelectionLabel($selection)->setFor($id);

            $html .= $radio . $label;
            $html .= $this->isInline ? ' ' : '<br>';
        }

        return $html;
    }

    /**
     *
     */
    protected function createSelectionRadio($selection): Radio
    {
        return new Radio();
    }
}
