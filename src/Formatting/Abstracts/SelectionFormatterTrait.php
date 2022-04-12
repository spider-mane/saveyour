<?php

namespace WebTheory\Saveyour\Formatting\Abstracts;

trait SelectionFormatterTrait
{
    protected function removeClearControl(&$value)
    {
        if (in_array('', $value)) {
            $value = array_filter($value, fn ($entry) => '' !== $entry);
        }
    }
}
