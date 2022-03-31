<?php

namespace WebTheory\Saveyour\Concerns;

trait SelectionFormatterTrait
{
    protected function removeClearControl(&$value)
    {
        if (in_array('', $value)) {
            $value = array_filter($value, function ($entry) {
                return '' !== $entry;
            });
        }
    }
}
