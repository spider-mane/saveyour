<?php

namespace Tests\Suites\Unit\Manager\Factory;

use Tests\Support\TestCase;
use WebTheory\Saveyour\Factory\DataManagerFactory;
use WebTheory\Saveyour\Data\FieldDataManagerCallback;

class DataManagerFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_a_data_manager()
    {
        $factory = new DataManagerFactory();

        $manager = 'callback';
        $args = [
            'get_data_callback' => fn () => null,
            'handle_data_callback' => fn () => null,
        ];

        $expected = new FieldDataManagerCallback(
            $args['get_data_callback'],
            $args['handle_data_callback']
        );

        $test = $factory->create($manager, $args);

        $this->assertEquals($expected, $test);
    }
}
