<?php

namespace WebTheory\Saveyour\Concerns;

use WebTheory\Saveyour\Contracts\OptionsProviderInterface;
use WebTheory\Saveyour\Elements\Option;

trait RendersOptionsTrait
{
    use IsSelectionFieldTrait;

    /**
     * @var OptionsProviderInterface
     */
    protected $selectionProvider;

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

    /**
     *
     */
    protected function defineSelectionText($selection)
    {
        return $this->selectionProvider->defineSelectionText($selection);
    }

    /**
     *
     */
    protected function renderSelection(): string
    {
        $html = '';

        foreach ($this->getSelectionData() as $selection) {
            $selected = $this->isSelectionSelected($this->defineSelectionValue($selection));

            $html .= $this->createOption($selection)->setSelected($selected);
        }

        return $html;
    }

    /**
     *
     */
    protected function createOption($selection): Option
    {
        return new Option(
            $this->defineSelectionText($selection),
            $this->defineSelectionValue($selection)
        );
    }
}
