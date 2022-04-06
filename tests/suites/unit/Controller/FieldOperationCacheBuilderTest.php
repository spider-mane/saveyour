<?php

namespace Tests\Suites\Unit\Controller;

use Tests\Support\TestCase;
use WebTheory\Saveyour\Controllers\ProcessedFieldReportBuilder;

class FieldOperationCacheBuilderTest extends TestCase
{
    public function testCanSetRequestVarPresent()
    {
        $builder = new ProcessedFieldReportBuilder();

        $builder->withRequestVarPresent(true);

        $this->assertTrue($builder->requestVarPresent());

        $builder->withRequestVarPresent(false);

        $this->assertFalse($builder->requestVarPresent());
    }

    public function testCanSetSanitizedInputValue()
    {
        $builder = new ProcessedFieldReportBuilder();

        $value = 'foobar';

        $builder->withSanitizedInputValue($value);

        $this->assertEquals($value, $builder->sanitizedInputValue());
    }

    public function testCanSetUpdateAttempted()
    {
        $builder = new ProcessedFieldReportBuilder();

        $builder->withUpdateAttempted(true);

        $this->assertTrue($builder->updateAttempted());

        $builder->withUpdateAttempted(false);

        $this->assertFalse($builder->updateAttempted());
    }

    public function testCanSetUpdateSuccessful()
    {
        $builder = new ProcessedFieldReportBuilder();

        $builder->withUpdateSuccessful(true);

        $this->assertTrue($builder->updateSuccessful());

        $builder->withUpdateSuccessful(false);

        $this->assertFalse($builder->updateSuccessful());
    }

    public function testCanSetRuleViolations()
    {
        $builder = new ProcessedFieldReportBuilder();

        $violations = [
            'test1' => 'foo',
            'test2' => 'bar',
        ];

        $builder->withRuleViolations($violations);

        $this->assertEquals($violations, $builder->ruleViolations());
    }
}
