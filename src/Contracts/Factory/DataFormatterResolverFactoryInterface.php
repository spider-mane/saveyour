<?php

namespace WebTheory\Saveyour\Contracts\Factory;

use WebTheory\Saveyour\Contracts\Formatting\DataFormatterInterface;

interface DataFormatterResolverFactoryInterface
{
    public function create(string $name, array $args = []): DataFormatterInterface;
}
