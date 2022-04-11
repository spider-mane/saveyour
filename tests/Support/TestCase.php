<?php

declare(strict_types=1);

namespace Tests\Support;

use Faker\Factory;
use Faker\Generator;
use Faker\UniqueGenerator;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class TestCase extends PHPUnitTestCase
{
    use MockeryPHPUnitIntegration;
    use ProphecyTrait;

    protected Generator $fake;

    protected UniqueGenerator $unique;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fake = $this->createFaker();
        $this->unique = $this->fake->unique();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        Mockery::close();
    }

    protected function makeMockeryOf($class, string ...$interfaces): MockInterface
    {
        return Mockery::mock($class, ...$interfaces);
    }

    protected function createFaker(): Generator
    {
        return Factory::create();
    }

    protected function dummyList(callable $generator, int $count = 10): array
    {
        return array_map(fn () => $generator($this->unique), range(1, $count));
    }
}
