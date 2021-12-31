<?php

use Tests\Support\TestCase;
use WebTheory\Saveyour\Controllers\AbstractField;

class AbstractFieldTest extends TestCase
{
    /**
     *
     */
    public function testCanInstantiateChild()
    {
        $requestVar = 'test';

        $child = new class($requestVar) extends AbstractField
        {
        };

        $this->assertEquals($requestVar, $child->getRequestVar());
    }
}
