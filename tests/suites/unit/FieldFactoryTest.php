<?php

use Tests\Support\TestCase;
use Respect\Validation\Validator;
use WebTheory\Saveyour\Controllers\FormFieldController;
use WebTheory\Saveyour\Factories\DataManagerFactory;
use WebTheory\Saveyour\Factories\FieldFactory;
use WebTheory\Saveyour\Factories\FormFieldFactory;
use WebTheory\Saveyour\Fields\Select;
use WebTheory\Saveyour\Managers\FieldDataManagerCallback;

class FieldFactoryTest
{
    /**
     *
     */
    protected function dummyCallback()
    {
        return function ($data) {
            return $data;
        };
    }

    // /**
    //  *
    //  */
    // public function testCreates_FormFieldController()
    // {
    //     $factory = new FieldFactory(
    //         new FormFieldFactory(),
    //         new DataManagerFactory()
    //     );

    //     $args = [
    //         'request_var' => 'foobar',

    //         'type' => [
    //             '@create' => 'select',
    //             'id' => 'foo',
    //             'name' => 'bar',
    //             'classlist' => ['foo', 'bar', 'baz'],
    //         ],

    //         'data' => [
    //             '@create' => 'callback',
    //             'get_data_callback' => $this->dummyCallback(),
    //             'handle_data_callback' => $this->dummyCallback(),
    //         ],

    //         'rules' => [
    //             'phone' => Validator::phone(),
    //             'email' => [
    //                 'validator' => Validator::email(),
    //                 'alert' => 'enter foobar'
    //             ]
    //         ]
    //     ];

    //     $field = $args['type'];
    //     $field = (new Select)
    //         ->setName($field['name'])
    //         ->setId($field['id'])
    //         ->setClasslist($field['classlist']);

    //     $manager = $args['data'];
    //     $manager = new FieldDataManagerCallback(
    //         $manager['get_data_callback'],
    //         $manager['handle_data_callback']
    //     );

    //     $expected = new FormFieldController($args['request_var'], $field, $manager);
    //     $expected->setRules($args['rules']);

    //     $test = $factory->create($args);

    //     $this->assertEquals($expected, $test);
    // }

    // /**
    //  *
    //  */
    // public function testCreatesFormFieldWithMagicMethod()
    // {
    //     $factory = new FieldFactory(
    //         new FormFieldFactory(),
    //         new DataManagerFactory()
    //     );

    //     $args = [
    //         'request_var' => 'foobar',

    //         'type' => [
    //             '@create' => 'select',
    //             'id' => 'foo',
    //             'name' => 'bar',
    //             'classlist' => ['foo', 'bar', 'baz'],
    //         ],

    //         'data' => [
    //             '@create' => 'callback',
    //             'get_data_callback' => function () {
    //             },
    //             'handle_data_callback' => function () {
    //             },
    //         ],

    //         'rules' => [
    //             'phone' => Validator::phone(),
    //             'email' => [
    //                 'validator' => Validator::email(),
    //                 'alert' => 'enter foobar'
    //             ]
    //         ]
    //     ];

    //     $field = $args['type'];
    //     $field = (new Select)
    //         ->setName($field['name'])
    //         ->setId($field['id'])
    //         ->setClasslist($field['classlist']);

    //     $manager = $args['data'];
    //     $manager = new FieldDataManagerCallback(
    //         $manager['get_data_callback'],
    //         $manager['handle_data_callback']
    //     );

    //     $expected = new FormFieldController($args['request_var'], $field, $manager);
    //     $expected->setRules($args['rules']);

    //     unset($args['type']['@create']);

    //     $test = $factory->select($args);

    //     $this->assertEquals($expected, $test);
    // }
}
