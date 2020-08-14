<?php

namespace WebTheory\Saveyour\Formatters;

use WebTheory\Saveyour\Contracts\DataFormatterInterface;

class LazyFormatter implements DataFormatterInterface
{
    /**
     *
     */
    public function formatData($value)
    {
        return $value;
    }

    /**
     *
     */
    public function formatInput($value)
    {
        return $value;
    }
}
