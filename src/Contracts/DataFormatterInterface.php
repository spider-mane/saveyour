<?php

namespace WebTheory\Saveyour\Contracts;

interface DataFormatterInterface
{
    /**
     *
     */
    public function formatData($value);

    /**
     *
     */
    public function formatInput($value);
}
