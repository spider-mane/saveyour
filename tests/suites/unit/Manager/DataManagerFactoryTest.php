<?php

use Tests\Support\TestCase;
use WebTheory\Saveyour\Factories\DataManagerFactory;
use WebTheory\Saveyour\Managers\FieldDataManagerCallback;

class DataManagerFactoryTest extends TestCase
{
    public function testCreatesFieldDataManager()
    {
        $factory = new DataManagerFactory();

        $manager = 'callback';
        $args = [
            'get_data_callback' => function () {
                return null;
            },

            'handle_data_callback' => function () {
                return null;
            },
        ];

        $expected = new FieldDataManagerCallback(
            $args['get_data_callback'],
            $args['handle_data_callback']
        );

        $test = $factory->create($manager, $args);

        $this->assertEquals($expected, $test);
    }
}
