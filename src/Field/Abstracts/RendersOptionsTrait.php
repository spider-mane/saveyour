<?php

namespace WebTheory\Saveyour\Field\Abstracts;

use WebTheory\Saveyour\Contracts\Field\Selection\OptionsProviderInterface;
use WebTheory\Saveyour\Field\Element\Option;

trait RendersOptionsTrait
{
    use SelectionFieldTrait;

    protected OptionsProviderInterface $selectionProvider;

    /**
     * Get the value of selectionProvider
     *
     * @return OptionsProviderInterface
     */
    public function getSelectionProvider(): OptionsProviderInterface
    {
        return $this->selectionProvider;
    }

    /**
     * Set the value of selectionProvider
     *
     * @param OptionsProviderInterface $selectionProvider
     *
     * @return self
     */
    public function setSelectionProvider(OptionsProviderInterface $selectionProvider)
    {
        $this->selectionProvider = $selectionProvider;

        return $this;
    }

    protected function defineSelectionText($selection): string
    {
        return $this->selectionProvider->defineSelectionText($selection);
    }

    protected function renderSelection(): string
    {
        $html = '';

        foreach ($this->getSelectionData() as $selection) {
            $selected = $this->isSelectionSelected($this->defineSelectionValue($selection));

            $html .= $this->createOption($selection)->setSelected($selected);
        }

        return $html;
    }

    protected function createOption($selection): Option
    {
        return new Option(
            $this->defineSelectionText($selection),
            $this->defineSelectionValue($selection)
        );
    }

    abstract protected function isSelectionSelected(string $value): bool;
}
