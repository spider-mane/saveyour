<?php

namespace WebTheory\Saveyour\Transformers;

use WebTheory\Saveyour\Contracts\DataTransformerInterface;

class LazyTransformer implements DataTransformerInterface
{
    /**
     *
     */
    public function transform($value)
    {
        return $value;
    }

    /**
     *
     */
    public function reverseTransform($value)
    {
        return $value;
    }
}
