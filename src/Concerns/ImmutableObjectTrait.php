<?php

namespace WebTheory\Saveyour\Concerns;

use LogicException;

trait ImmutableObjectTrait
{
    protected function throwModificationException()
    {
        throw new LogicException('properties in ' . static::class . ' cannot be modified.');
    }

    public function offsetSet($offset, $value)
    {
        $this->throwModificationException();
    }

    public function offsetUnset($offset)
    {
        $this->throwModificationException();
    }
}
