<?php

use PHPUnit\Framework\TestCase;
use WebTheory\Saveyour\Formatters\DelimitedArrayListFormatter;

class DelimitedArrayListFormatterTest extends TestCase
{
    public function testProperlyFormats()
    {
        $formatter = new DelimitedArrayListFormatter;

        $expected = 'foo, bar';

        $value = ['foo', 'bar'];

        $this->assertEquals($expected, $formatter->formatData($value));
    }

    public function testProperlyReverseFormats()
    {
        $formatter = new DelimitedArrayListFormatter;

        $expected = ['foo', 'bar'];

        $value = 'foo, bar';

        $this->assertEquals($expected, $formatter->formatInput($value));
    }
}
