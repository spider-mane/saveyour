<?php

namespace WebTheory\Saveyour\Formatters;

use WebTheory\Saveyour\Contracts\DataFormatterInterface;

class IntToStringDataFormatter implements DataFormatterInterface
{
    public function formatData($value)
    {
        return is_array($value) ? array_map('strval', $value) : (string) $value;
    }

    public function formatInput($value)
    {
        return is_array($value) ? array_map('intval', $value) : (int) $value;
    }
}
