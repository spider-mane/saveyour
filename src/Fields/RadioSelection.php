<?php

namespace WebTheory\Saveyour\Fields;

use WebTheory\Saveyour\Concerns\SingleValueSelectionTrait;
use WebTheory\Saveyour\Contracts\FormFieldInterface;

class RadioSelection extends AbstractCompositeSelectionField implements FormFieldInterface
{
    use SingleValueSelectionTrait;

    /**
     * @var bool
     */
    protected $isInline = false;

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
    protected function renderHtmlMarkup(): string
    {
        $html = '';

        foreach ($this->getSelectionData() as $selection) {
            $id = $this->defineSelectionId($selection);
            $value = $this->defineSelectionValue($selection);

            $checked = $this->isSelectionSelected($value);

            $html .= $this->createSelectionRadio($selection)->setChecked($checked);
            $html .= $this->createSelectionLabel($selection)->setFor($id);

            if ($this->isInline) {
                $html .= '<br>';
            }
        }

        return $html;
    }

    /**
     *
     */
    protected function createSelectionRadio($selection): Radio
    {
        return (new Radio())
            ->setName($this->name)
            ->setValue($this->defineSelectionValue($selection));
    }
}
