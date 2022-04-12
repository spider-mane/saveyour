<?php

namespace WebTheory\Saveyour\Contracts\Formatting;

interface DataFormatterInterface extends InputFormatterInterface
{
    public function formatData($value);
}
