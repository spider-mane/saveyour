<?php

declare(strict_types=1);

namespace Tests\Support;

use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCase extends PHPUnitTestCase
{
    protected Generator $faker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = $this->createFaker();
    }

    protected function createFaker(): Generator
    {
        return Factory::create();
    }
}
