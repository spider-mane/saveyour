<?php

namespace Tests\Suites\Unit\Field\Selection;

use InvalidArgumentException;
use Tests\Support\UnitTestCase;
use WebTheory\Saveyour\Field\Selection\StateSelectOptions;

class StateSelectOptionsTest extends UnitTestCase
{
    protected function getSelection($provider)
    {
        $value = [];

        foreach ($provider->provideSelectionsData() as $selection) {
            $value[$provider->defineSelectionValue($selection)] = $provider->defineSelectionText($selection);
        }

        return $value;
    }

    /**
     * @test
     */
    public function it_generates_list_of_states()
    {
        $states = StateSelectOptions::STATES;

        $options = new StateSelectOptions();

        $this->assertEquals($states, $this->getSelection($options));
    }

    /**
     * @test
     */
    public function it_generates_armed_forces_and_territories()
    {
        $expected = array_merge(
            StateSelectOptions::STATES,
            StateSelectOptions::TERRITORIES,
            StateSelectOptions::ARMED_FORCES
        );

        $instance = new StateSelectOptions(['States', 'Territories', 'ArmedForces']);

        $this->assertEquals($expected, $this->getSelection($instance));
    }

    /**
     * @test
     */
    public function it_sorts_states_with_armed_forces_and_territories()
    {
        $values = array_merge(
            StateSelectOptions::STATES,
            StateSelectOptions::TERRITORIES,
            StateSelectOptions::ARMED_FORCES
        );
        asort($values);

        $instance = new StateSelectOptions(['States', 'Territories', 'ArmedForces'], 'name', true);

        $this->assertEquals($values, $this->getSelection($instance));
    }

    /**
     * @test
     */
    public function it_throws_exception_with_invalid_group()
    {
        $this->expectException(InvalidArgumentException::class);

        $values = array_merge(
            StateSelectOptions::STATES,
            StateSelectOptions::ARMED_FORCES
        );

        $instance = new StateSelectOptions(['States', 'InvalidTest', 'ArmedForces']);

        $this->assertEquals($values, $this->getSelection($instance));
    }
}
