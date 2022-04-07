<?php

declare(strict_types=1);

namespace Tests\Support;

use Faker\Factory;
use Faker\Generator;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCase extends PHPUnitTestCase
{
    use MockeryPHPUnitIntegration;

    protected Generator $faker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = $this->createFaker();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }

    protected function makeMockeryOf(string $class)
    {
        return Mockery::mock($class);
    }

    protected function createFaker(): Generator
    {
        return Factory::create();
    }
}
