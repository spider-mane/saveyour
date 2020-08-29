<?php

namespace WebTheory\Saveyour\Formatters;

use WebTheory\Saveyour\Contracts\DataFormatterInterface;

class CombinationFormatter implements DataFormatterInterface
{
    /**
     * @var DataFormatterInterface[]
     */
    protected $formatters = [];

    /**
     *
     */
    public function __construct(DataFormatterInterface ...$formatters)
    {
        $this->formatters = $formatters;
    }

    /**
     * Get the value of formatters
     *
     * @return array
     */
    public function getFormatters(): array
    {
        return $this->formatters;
    }

    /**
     *
     */
    public function formatData($value)
    {
        foreach ($this->formatters as $formatter) {
            $value = $formatter->formatData($value);
        }

        return $value;
    }

    /**
     *
     */
    public function formatInput($value)
    {
        foreach (array_reverse($this->formatters) as $formatter) {
            $value = $formatter->formatInput($value);
        }

        return $value;
    }
}
