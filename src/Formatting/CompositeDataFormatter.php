<?php

namespace WebTheory\Saveyour\Formatting;

use WebTheory\Saveyour\Contracts\Formatting\DataFormatterInterface;

class CompositeDataFormatter implements DataFormatterInterface
{
    /**
     * @var DataFormatterInterface[]
     */
    protected array $formatters = [];

    public function __construct(DataFormatterInterface ...$formatters)
    {
        $this->formatters = $formatters;
    }

    public function formatData($value)
    {
        foreach ($this->formatters as $formatter) {
            $value = $formatter->formatData($value);
        }

        return $value;
    }

    public function formatInput($value)
    {
        foreach (array_reverse($this->formatters) as $formatter) {
            $value = $formatter->formatInput($value);
        }

        return $value;
    }
}
