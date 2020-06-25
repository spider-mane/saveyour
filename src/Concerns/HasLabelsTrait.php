<?php

namespace WebTheory\Saveyour\Concerns;

trait HasLabelsTrait
{
    use LabelMaker;

    /**
     * @var array
     */
    protected $labelOptions = [];

    /**
     * Get the value of labelOptions
     *
     * @return array
     */
    public function getLabelOptions(): array
    {
        return $this->labelOptions;
    }

    /**
     * Set the value of labelOptions
     *
     * @param array $labelOptions
     *
     * @return self
     */
    public function setLabelOptions(array $labelOptions)
    {
        $this->labelOptions = $labelOptions;

        return $this;
    }
}
