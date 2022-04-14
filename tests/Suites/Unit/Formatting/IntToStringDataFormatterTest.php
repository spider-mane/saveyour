<?php

namespace Tests\Suites\Unit\Formatting;

use Tests\Support\UnitTestCase;
use WebTheory\Saveyour\Formatting\IntToStringDataFormatter;

class IntToStringDataFormatterTest extends UnitTestCase
{
    protected IntToStringDataFormatter $sut;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = new IntToStringDataFormatter();
    }

    /**
     * @test
     */
    public function it_converts_an_integer_to_its_string_value()
    {
        # Arrange
        $intVal = $this->fake->randomNumber();
        $strVal = (string) $intVal;

        # Assert
        $this->assertEquals($strVal, $this->sut->formatData($intVal));
    }

    /**
     * @test
     */
    public function it_converts_a_numeric_string_to_integer()
    {
        # Arrange
        $intVal = $this->fake->randomNumber();
        $strVal = (string) $intVal;

        # Assert
        $this->assertEquals($intVal, $this->sut->formatInput($strVal));
    }

    /**
     * @test
     */
    public function it_converts_an_array_of_integers_to_respective_string_values()
    {
        # Arrange
        $intVals = $this->dummyList(fn () => $this->fake->randomNumber());
        $strVals = array_map('strval', $intVals);

        # Assert
        $this->assertEquals($strVals, $this->sut->formatData($intVals));
    }

    /**
     * @test
     */
    public function it_converts_an_array_of_numeric_strings_to_respective_integer_values()
    {
        # Arrange
        $intVals = $this->dummyList(fn () => $this->fake->randomNumber());
        $strVals = array_map('strval', $intVals);

        # Assert
        $this->assertEquals($intVals, $this->sut->formatData($strVals));
    }
}
