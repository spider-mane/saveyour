<?php

namespace WebTheory\Saveyour\Contracts;

interface DataFormatterInterface extends InputFormatterInterface
{
    public function formatData($value);
}
