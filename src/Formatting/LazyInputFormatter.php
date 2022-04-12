<?php

namespace WebTheory\Saveyour\Formatting;

use WebTheory\Saveyour\Contracts\Formatting\InputFormatterInterface;

class LazyInputFormatter implements InputFormatterInterface
{
    public function formatInput($input)
    {
        return $input;
    }
}
