<?php

namespace WebTheory\Saveyour\Formatting;

use WebTheory\Saveyour\Contracts\Formatting\DataFormatterInterface;

class ArrayToListDataFormatter implements DataFormatterInterface
{
    protected string $delimiter = ', ';

    public function __construct(?string $delimiter = null)
    {
        $this->delimiter = $delimiter ?? $this->delimiter;
    }

    public function formatData($value)
    {
        return implode($this->delimiter, $value);
    }

    public function formatInput($value)
    {
        return explode($this->delimiter, $value);
    }
}
