<?php

namespace Tests\Suites\Unit\Formatting;

use Tests\Support\TestCase;
use WebTheory\Saveyour\Formatting\ArrayToListDataFormatter;

class ArrayToListDataFormatterTest extends TestCase
{
    /**
     * @test
     */
    public function it_converts_data_as_an_array_to_a_delimiter_separated_list()
    {
        # Arrange
        $delimiter = ',';
        $array = $this->dummyList(fn () => $this->unique->word);

        $sut = new ArrayToListDataFormatter($delimiter);
        $expected = implode($delimiter, $array);

        # Act
        $result = $sut->formatData($array);

        # Assert
        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function it_converts_input_as_a_delimiter_separated_list_to_an_array()
    {
        # Arrange
        $delimiter = ',';
        $expected = $this->dummyList(fn () => $this->unique->word);
        $list = implode($delimiter, $expected);

        $sut = new ArrayToListDataFormatter($delimiter);

        # Act
        $result = $sut->formatInput($list);

        # Assert
        $this->assertEquals($expected, $result);
    }
}
