<?php

use Tests\Support\TestCase;
use WebTheory\Saveyour\Contracts\FieldOperationCacheInterface;
use WebTheory\Saveyour\Controllers\FieldOperationCache;
use WebTheory\Saveyour\Controllers\FormDataProcessingCache;
use WebTheory\Saveyour\Controllers\FormProcessingCache;

class FormProcessingCacheTest extends TestCase
{
    /**
     *
     */
    protected $values;

    /**
     * @var FormProcessingCache
     */
    protected $testInstance;

    /**
     * @var FieldOperationCacheInterface
     */
    protected $testFieldCache;

    /**
     *
     */
    protected $inputValues = [
        'request_var_present' => true,
        'sanitized_input_value' => 'foobar',
        'update_attempted' => true,
        'update_successful' => true,
        'rule_violations' => ['foo' => 'bar'],
    ];

    /**
     *
     */
    public function setup(): void
    {
        $this->testFieldCache = new FieldOperationCache(
            $this->inputValues['request_var_present'],
            $this->inputValues['sanitized_input_value'],
            $this->inputValues['update_attempted'],
            $this->inputValues['update_successful'],
            $this->inputValues['rule_violations']
        );

        $this->values = [
            'request_violations' => ['foo' => 'bar'],
            'input_violations' => ['test' => $this->testFieldCache->ruleViolations()],
            'input_results' => ['test' => $this->testFieldCache],
            'processing_results' => ['test' => new FormDataProcessingCache()],
        ];

        $this->testInstance = (new FormProcessingCache())
            ->withInputResults($this->values['input_results'])
            ->withInputViolations($this->values['input_violations'])
            ->withRequestViolations($this->values['request_violations'])
            ->withProcessingResults($this->values['processing_results']);
    }

    /**
     *
     */
    public function testCanSetAndGetInputResults()
    {
        $cache = new FormProcessingCache();
        $results = ['test' => $this->testFieldCache];

        $cache->withInputResults($results);

        $this->assertEquals($results, $cache->inputResults());
    }

    /**
     *
     */
    public function testCanSetAndGetInputViolations()
    {
        $cache = new FormProcessingCache();
        $violations = ['test' => $this->testFieldCache->ruleViolations()];

        $cache->withInputViolations($violations);

        $this->assertEquals($violations, $cache->inputViolations());
    }

    /**
     *
     */
    public function testCanSetAndGetRequestViolations()
    {
        $cache = new FormProcessingCache();
        $violations = ['foo' => 'bar'];

        $cache->withRequestViolations($violations);

        $this->assertEquals($violations, $cache->requestViolations());
    }

    /**
     *
     */
    public function testCanGetValueViaArrayAccess()
    {
        $expected = $this->values['input_results'];

        $this->assertTrue(isset($this->testInstance['input_results']));
        $this->assertEquals($expected, $this->testInstance['input_results']);
    }

    /**
     *
     */
    public function testCanSerializeToJson()
    {
        $expected = json_encode($this->values);

        $this->assertEquals($expected, json_encode($this->testInstance));
    }

    /**
     *
     */
    public function testCannotModifyViaArrayAccess()
    {
        $this->expectException(LogicException::class);

        $key = 'input_results';

        $expected = $this->values[$key];

        $this->testInstance[] = 'fail';

        $this->assertEquals($expected, $this->testInstance->inputResults());

        unset($this->testInstance[$key]);

        $this->assertNotNull($this->testInstance->inputResults());
    }
}
