<?php

namespace WebTheory\Saveyour\Formatters;

use WebTheory\Saveyour\Contracts\InputFormatterInterface;

class LazyInputFormatter implements InputFormatterInterface
{
    public function formatInput($input)
    {
        return $input;
    }
}
