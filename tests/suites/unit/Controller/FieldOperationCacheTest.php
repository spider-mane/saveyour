<?php

namespace Tests\Suites\Unit\Controller;

use Tests\Support\TestCase;
use WebTheory\Saveyour\Controllers\FieldOperationCache;

class FieldOperationCacheTest extends TestCase
{
    /**
     * @var FieldOperationCache
     */
    protected $testInstance;

    protected $values = [
        'request_var_present' => true,
        'sanitized_input_value' => 'foobar',
        'update_attempted' => true,
        'update_successful' => true,
        'rule_violations' => ['foo' => 'bar'],
    ];

    public function setup(): void
    {
        $this->testInstance = new FieldOperationCache(
            $this->values['request_var_present'],
            $this->values['sanitized_input_value'],
            $this->values['update_attempted'],
            $this->values['update_successful'],
            $this->values['rule_violations']
        );
    }

    public function testCanGetRequestVarPresent()
    {
        $expected = $this->values['request_var_present'];
        $actual = $this->testInstance->requestVarPresent();

        $this->assertEquals($expected, $actual);
    }

    public function testCanGetSanitizedInputValue()
    {
        $expected = $this->values['sanitized_input_value'];
        $actual = $this->testInstance->sanitizedInputValue();

        $this->assertEquals($expected, $actual);
    }

    public function testCanGetUpdateAttempted()
    {
        $expected = $this->values['update_attempted'];
        $actual = $this->testInstance->updateAttempted();

        $this->assertEquals($expected, $actual);
    }

    public function testCanGetUpdateSuccessful()
    {
        $expected = $this->values['update_successful'];
        $actual = $this->testInstance->updateSuccessful();

        $this->assertEquals($expected, $actual);
    }

    public function testCanGetRuleViolations()
    {
        $expected = $this->values['rule_violations'];
        $actual = $this->testInstance->ruleViolations();

        $this->assertEquals($expected, $actual);
    }

    public function testCanGetValueViaArrayAccess()
    {
        $expected = $this->values['sanitized_input_value'];

        $this->assertTrue(isset($this->testInstance['sanitized_input_value']));
        $this->assertEquals($expected, $this->testInstance['sanitized_input_value']);
    }

    public function testCanSerializeToJson()
    {
        $expected = json_encode($this->values);

        $this->assertEquals($expected, json_encode($this->testInstance));
    }

    public function testCannotModifyViaArrayAccess()
    {
        $this->expectException(LogicException::class);

        $key = 'sanitized_input_value';

        $expected = $this->values[$key];

        $this->testInstance[] = 'fail';

        $this->assertEquals($expected, $this->testInstance->sanitizedInputValue());

        unset($this->testInstance[$key]);

        $this->assertNotNull($this->testInstance->sanitizedInputValue());
    }
}
