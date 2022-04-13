<?php

namespace Tests\Suites\Unit\Formatting;

use Tests\Support\TestCase;
use WebTheory\Saveyour\Contracts\Formatting\DataFormatterInterface;
use WebTheory\Saveyour\Formatting\CompositeDataFormatter;

class CompositeDataFormatterTest extends TestCase
{
    protected CompositeDataFormatter $sut;

    protected array $mockFormatters;

    protected array $dummyValues;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dummyValues = $this->dummyList(
            fn () => $this->unique->sentence,
            random_int(5, 10)
        );

        $this->mockFormatters = array_map(
            fn () => $this->createMock(DataFormatterInterface::class),
            $this->dummyValues
        );

        $this->sut = new CompositeDataFormatter(...$this->mockFormatters);
    }

    /**
     * @test
     */
    public function it_formats_data_in_order_data_formatters_were_provided()
    {
        # Expect
        foreach ($this->mockFormatters as $formatter) {
            $formatter->expects($this->once())
                ->method('formatData')
                ->with(current($this->dummyValues))
                ->willReturn(next($this->dummyValues) ?: end($this->dummyValues));
        }

        # Act
        $result = $this->sut->formatData($this->dummyValues[0]);

        # Assert
        $this->assertEquals(end($this->dummyValues), $result);
    }

    /**
     * @test
     */
    public function it_formats_input_in_reverse_order_formatters_were_provided()
    {
        # Expect
        foreach (array_reverse($this->mockFormatters) as $formatter) {
            $formatter->expects($this->once())
                ->method('formatInput')
                ->with(current($this->dummyValues))
                ->willReturn(next($this->dummyValues) ?: end($this->dummyValues));
        }

        # Act
        $result = $this->sut->formatInput($this->dummyValues[0]);

        # Assert
        $this->assertEquals(end($this->dummyValues), $result);
    }
}
