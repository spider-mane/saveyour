<?php

use PHPUnit\Framework\TestCase;
use WebTheory\Saveyour\Contracts\DataFormatterInterface;
use WebTheory\Saveyour\Formatters\CombinationFormatter;
use WebTheory\Saveyour\Formatters\DelimitedArrayListFormatter;
use WebTheory\Saveyour\Formatters\LazyFormatter;

class CombinationFormatterTest extends TestCase
{
    /**
     *
     */
    protected function getDummyFormatter(int $count)
    {
        return new class ($count) implements DataFormatterInterface
        {
            protected $count;

            public function __construct(int $count)
            {
                $this->count = $count;
            }

            public function formatData($value)
            {
                return array_fill(0, $this->count, $value);
            }

            public function formatInput($value)
            {
                return $value[0];
            }
        };
    }

    /**
     * @test
     */
    public function can_be_instantiated_with_DataFormatters()
    {
        $formatters = [
            new LazyFormatter,
            new DelimitedArrayListFormatter
        ];

        $combination = new CombinationFormatter(...$formatters);

        $this->assertEquals($formatters, $combination->getFormatters());
    }

    /**
     * @test
     */
    public function formats_data()
    {
        $test = 'foobar';
        $expected = 'foobar, foobar, foobar, foobar, foobar';

        $formatter = new CombinationFormatter(
            $this->getDummyFormatter(count(explode(', ', $expected))),
            new DelimitedArrayListFormatter
        );

        $this->assertEquals($expected, $formatter->formatData($test));
    }

    /**
     * @test
     */
    public function formats_input()
    {
        $test = 'foobar, foobar, foobar, foobar, foobar';
        $expected = 'foobar';

        $formatter = new CombinationFormatter(
            $this->getDummyFormatter(count(explode(', ', $test))),
            new DelimitedArrayListFormatter
        );

        $this->assertEquals($expected, $formatter->formatInput($test));
    }
}
