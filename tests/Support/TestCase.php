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

    protected function createFaker(): Generator
    {
        return Factory::create();
    }

    protected function makeMockeryOf($class, string ...$interfaces): MockInterface
    {
        return Mockery::mock($class, ...$interfaces);
    }

    protected function dummyList(callable $generator, int $count = 10): array
    {
        return array_map(fn () => $generator(), range(1, $count));
    }

    protected function dummyMap(callable $generator, array $keys): array
    {
        return array_map(fn () => $generator(), array_flip($keys));
    }

    protected function dummyKeyMap(callable $keyGen, callable $valueGen, int $count = 10): array
    {
        return $this->dummyMap($valueGen, $this->dummyList($keyGen, $count));
    }
}
