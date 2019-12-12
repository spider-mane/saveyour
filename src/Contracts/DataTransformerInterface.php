<?php

namespace WebTheory\Saveyour\Contracts;

interface DataTransformerInterface
{
    /**
     *
     */
    public function transform($value);

    /**
     *
     */
    public function reverseTransform($value);
}
