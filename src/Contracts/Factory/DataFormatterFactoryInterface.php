<?php

namespace WebTheory\Saveyour\Contracts\Factory;

use WebTheory\Saveyour\Contracts\Formatting\DataFormatterInterface;

interface DataFormatterFactoryInterface
{
    public function create(array $args = []): DataFormatterInterface;
}
