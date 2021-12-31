<?php

use Tests\Support\TestCase;
use WebTheory\Saveyour\Factories\FormFieldFactory;
use WebTheory\Saveyour\Fields\Select;

class FormFieldFactoryTest extends TestCase
{
    /**
     *
     */
    public function testCreatesFormFieldFactory()
    {
        $factory = new FormFieldFactory();

        $field = 'select';
        $args = [
            'id' => 'foo',
            'name' => 'bar',
            'classlist' => ['foo', 'bar', 'baz'],
        ];

        $expected = (new Select)
            ->setName($args['name'])
            ->setId($args['id'])
            ->setClasslist($args['classlist']);

        $test = $factory->create($field, $args);

        $this->assertEquals($expected, $test);
    }
}
