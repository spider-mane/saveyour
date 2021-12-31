<?php

use Tests\Support\TestCase;
use WebTheory\Saveyour\Controllers\FieldOperationCacheBuilder;

class FieldOperationCacheBuilderTest extends TestCase
{
    public function testCanSetRequestVarPresent()
    {
        $builder = new FieldOperationCacheBuilder;

        $builder->withRequestVarPresent(true);

        $this->assertTrue($builder->requestVarPresent());

        $builder->withRequestVarPresent(false);

        $this->assertFalse($builder->requestVarPresent());
    }

    public function testCanSetSanitizedInputValue()
    {
        $builder = new FieldOperationCacheBuilder;

        $value = 'foobar';

        $builder->withSanitizedInputValue($value);

        $this->assertEquals($value, $builder->sanitizedInputValue());
    }

    public function testCanSetUpdateAttempted()
    {
        $builder = new FieldOperationCacheBuilder;

        $builder->withUpdateAttempted(true);

        $this->assertTrue($builder->updateAttempted());

        $builder->withUpdateAttempted(false);

        $this->assertFalse($builder->updateAttempted());
    }

    public function testCanSetUpdateSuccessful()
    {
        $builder = new FieldOperationCacheBuilder;

        $builder->withUpdateSuccessful(true);

        $this->assertTrue($builder->updateSuccessful());

        $builder->withUpdateSuccessful(false);

        $this->assertFalse($builder->updateSuccessful());
    }

    public function testCanSetRuleViolations()
    {
        $builder = new FieldOperationCacheBuilder;

        $violations = [
            'test1' => 'foo',
            'test2' => 'bar',
        ];

        $builder->withRuleViolations($violations);

        $this->assertEquals($violations, $builder->ruleViolations());
    }
}
