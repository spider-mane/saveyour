<?php

namespace WebTheory\Saveyour\Formatting;

use WebTheory\Saveyour\Contracts\Formatting\DataFormatterInterface;

class LazyDataFormatter implements DataFormatterInterface
{
    public function formatData($value)
    {
        return $value;
    }

    public function formatInput($value)
    {
        return $value;
    }
}
