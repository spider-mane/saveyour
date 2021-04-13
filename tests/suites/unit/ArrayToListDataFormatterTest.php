<?php

use PHPUnit\Framework\TestCase;
use WebTheory\Saveyour\Formatters\ArrayToListDataFormatter;

class ArrayToListDataFormatterTest extends TestCase
{
    public function testProperlyFormats()
    {
        $formatter = new ArrayToListDataFormatter;

        $expected = 'foo, bar';

        $value = ['foo', 'bar'];

        $this->assertEquals($expected, $formatter->formatData($value));
    }

    public function testProperlyReverseFormats()
    {
        $formatter = new ArrayToListDataFormatter;

        $expected = ['foo', 'bar'];

        $value = 'foo, bar';

        $this->assertEquals($expected, $formatter->formatInput($value));
    }
}
