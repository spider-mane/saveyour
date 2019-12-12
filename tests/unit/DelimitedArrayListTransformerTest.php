<?php

use PHPUnit\Framework\TestCase;
use WebTheory\Saveyour\Transformers\DelimitedArrayListTransformer;

class DelimitedArrayListTransformerTest extends TestCase
{
    public function testProperlyTransforms()
    {

        $transformer = new DelimitedArrayListTransformer;

        $expected = 'foo, bar';

        $value = ['foo', 'bar'];

        $this->assertEquals($expected, $transformer->transform($value));
    }

    public function testProperlyReverseTransforms()
    {

        $transformer = new DelimitedArrayListTransformer;

        $expected = ['foo', 'bar'];

        $value = 'foo, bar';

        $this->assertEquals($expected, $transformer->reverseTransform($value));
    }
}
