<?php

use PHPUnit\Framework\TestCase;
use WebTheory\Saveyour\Fields\Selections\StateSelectOptions;

class SelectOptionsTest extends TestCase
{
    /**
     * @test
     */
    public function test_generates_list_of_states()
    {
        $states = StateSelectOptions::STATES;

        $options = new StateSelectOptions();

        $this->assertEquals($states, $options->getSelection());
    }

    /**
     * @test
     */
    public function test_generates_armed_forces_and_territories()
    {
        $expected = array_merge(
            StateSelectOptions::STATES,
            StateSelectOptions::TERRITORIES,
            StateSelectOptions::ARMED_FORCES
        );

        $instance = new StateSelectOptions(['States', 'Territories', 'ArmedForces']);

        $this->assertEquals($expected, $instance->getSelection());
    }

    /**
     * @test
     */
    public function test_sorts_states_with_armed_forces_and_territories()
    {
        $values = array_merge(
            StateSelectOptions::STATES,
            StateSelectOptions::TERRITORIES,
            StateSelectOptions::ARMED_FORCES
        );
        sort($values);

        $instance = new StateSelectOptions(['States', 'Territories', 'ArmedForces'], true);

        $this->assertEquals($values, $instance->getSelection());
    }

    /**
     * @test
     */
    public function test_throws_exception_with_invalid_group()
    {
        $this->expectException(InvalidArgumentException::class);

        $values = array_merge(
            StateSelectOptions::STATES,
            StateSelectOptions::ARMED_FORCES
        );

        $instance = new StateSelectOptions(['States', 'InvalidTest', 'ArmedForces']);

        $this->assertEquals($values, $instance->getSelection());
    }
}
