<?php

use Tests\Support\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use WebTheory\Saveyour\Concerns\ShantHaveCurrentData;
use WebTheory\Saveyour\Contracts\FieldDataManagerInterface;

class ShantHaveCurrentDataTest extends TestCase
{
    protected $dummyClass;

    public function generateDummyClass(): FieldDataManagerInterface
    {
        return new class implements FieldDataManagerInterface
        {
            use ShantHaveCurrentData;

            public function handleSubmittedData(ServerRequestInterface $request, $data): bool
            {
                return true;
            }
        };
    }

    public function testMethodIsCompatibleWithTargetedInterfaceMethod()
    {
        $class = $this->generateDummyClass();

        $this->assertInstanceOf(FieldDataManagerInterface::class, $class);
    }

    public function testMethodReturnsEmptyString()
    {
        $class = $this->generateDummyClass();
        $request = $this->createMock(ServerRequestInterface::class);

        $this->assertEquals('', $class->getCurrentData($request));
    }
}
