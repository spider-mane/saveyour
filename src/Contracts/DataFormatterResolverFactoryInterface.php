<?php

namespace WebTheory\Saveyour\Contracts;

interface DataFormatterResolverFactoryInterface
{
    public function create(string $name, array $args = []): DataFormatterInterface;
}
