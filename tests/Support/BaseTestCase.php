<?php

declare(strict_types=1);

namespace Tests\Support;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use WebTheory\UnitUtils\Concerns\AssertionsTrait;
use WebTheory\UnitUtils\Concerns\FakeGeneratorTrait;
use WebTheory\UnitUtils\Concerns\FormattedDataSetsTrait;
use WebTheory\UnitUtils\Concerns\MockeryTrait;
use WebTheory\UnitUtils\Concerns\ProphecyTrait;
use WebTheory\UnitUtils\Concerns\SystemTrait;

abstract class BaseTestCase extends PHPUnitTestCase
{
    use AssertionsTrait;
    use FakeGeneratorTrait;
    use FormattedDataSetsTrait;
    use MockeryTrait;
    use ProphecyTrait;
    use SystemTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->init();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->closeMockery();
        $this->tearDownProphecy();
    }

    protected function init(): void
    {
        $this->initFaker();
    }

    protected function getSupportPath(string $path = '')
    {
        return __DIR__ . $path;
    }
}
