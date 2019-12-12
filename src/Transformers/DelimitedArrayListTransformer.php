<?php

namespace WebTheory\Saveyour\Transformers;

use WebTheory\Saveyour\Contracts\DataTransformerInterface;

class DelimitedArrayListTransformer implements DataTransformerInterface
{
    /**
     *
     */
    protected $delimiter = ', ';

    /**
     *
     */
    public function __construct(string $delimiter = ', ')
    {
        $this->delimiter = $delimiter;
    }

    /**
     *
     */
    public function transform($value)
    {
        return implode($this->delimiter, $value);
    }

    /**
     *
     */
    public function reverseTransform($value)
    {
        return explode($this->delimiter, $value);
    }
}
