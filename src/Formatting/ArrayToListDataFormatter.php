<?php

namespace WebTheory\Saveyour\Formatting;

use WebTheory\Saveyour\Contracts\DataFormatterInterface;

class ArrayToListDataFormatter implements DataFormatterInterface
{
    protected string $delimiter = ', ';

    public function __construct(?string $delimiter = null)
    {
        $delimiter && $this->delimiter = $delimiter;
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
