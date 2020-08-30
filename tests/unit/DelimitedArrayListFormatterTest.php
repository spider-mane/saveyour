<?php

use PHPUnit\Framework\TestCase;
use WebTheory\Saveyour\Formatters\ArrayToStringDataFormatter;

class DelimitedArrayListFormatterTest extends TestCase
{
    public function testProperlyFormats()
    {
        $formatter = new ArrayToStringDataFormatter;

        $expected = 'foo, bar';

        $value = ['foo', 'bar'];

        $this->assertEquals($expected, $formatter->formatData($value));
    }

    public function testProperlyReverseFormats()
    {
        $formatter = new ArrayToStringDataFormatter;

        $expected = ['foo', 'bar'];

        $value = 'foo, bar';

        $this->assertEquals($expected, $formatter->formatInput($value));
    }
}
