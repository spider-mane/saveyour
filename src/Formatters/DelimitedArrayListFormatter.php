<?php

namespace WebTheory\Saveyour\Formatters;

use WebTheory\Saveyour\Contracts\DataFormatterInterface;

class DelimitedArrayListFormatter implements DataFormatterInterface
{
    /**
     *
     */
    protected $delimiter = ', ';

    /**
     *
     */
    public function __construct(?string $delimiter = null)
    {
        $delimiter && $this->delimiter = $delimiter;
    }

    /**
     *
     */
    public function formatData($value)
    {
        return implode($this->delimiter, $value);
    }

    /**
     *
     */
    public function formatInput($value)
    {
        return explode($this->delimiter, $value);
    }
}
