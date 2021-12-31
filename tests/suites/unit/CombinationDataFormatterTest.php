<?php

use Tests\Support\TestCase;
use WebTheory\Saveyour\Contracts\DataFormatterInterface;
use WebTheory\Saveyour\Formatters\ArrayToListDataFormatter;
use WebTheory\Saveyour\Formatters\CombinationDataFormatter;
use WebTheory\Saveyour\Formatters\LazyDataFormatter;

class CombinationDataFormatterTest extends TestCase
{
    /**
     *
     */
    protected function getDummyFormatter(int $count)
    {
        return new class($count) implements DataFormatterInterface
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
            new LazyDataFormatter,
            new ArrayToListDataFormatter
        ];

        $combination = new CombinationDataFormatter(...$formatters);

        $this->assertEquals($formatters, $combination->getFormatters());
    }

    /**
     * @test
     */
    public function formats_data()
    {
        $test = 'foobar';
        $expected = 'foobar, foobar, foobar, foobar, foobar';

        $formatter = new CombinationDataFormatter(
            $this->getDummyFormatter(count(explode(', ', $expected))),
            new ArrayToListDataFormatter
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

        $formatter = new CombinationDataFormatter(
            $this->getDummyFormatter(count(explode(', ', $test))),
            new ArrayToListDataFormatter
        );

        $this->assertEquals($expected, $formatter->formatInput($test));
    }
}
