<?php

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use WebTheory\Saveyour\Contracts\FormDataProcessingCacheInterface;
use WebTheory\Saveyour\Controllers\FieldOperationCacheBuilder;
use WebTheory\Saveyour\Processors\AbstractFormDataProcessor;

class AbstractFormDataProcessorTest extends TestCase
{
    protected function generateDummyClass(): AbstractFormDataProcessor
    {
        return new class extends AbstractFormDataProcessor
        {
            public $fields;
            public $extractedValues;
            public $valueUpdated;
            public $allFieldsPresent;

            public function process(ServerRequestInterface $request, array $results): ?FormDataProcessingCacheInterface
            {
                $this->extractedValues = $this->extractValues($results);
                $this->valueUpdated = $this->valueUpdated($results);
                $this->allFieldsPresent = $this->allFieldsPresent($results);

                return null;
            }
        };
    }

    public function testCanSetAndGetFields()
    {
        $processor = $this->generateDummyClass();

        $fields = [
            'test1' => 'foo',
            'test2' => 'bar',
            'test3' => 'baz'
        ];

        $processor->setFields($fields);

        $this->assertEquals($fields, $processor->getFields());
    }

    public function testCanAddAndGetSingleField()
    {
        $processor = $this->generateDummyClass();

        $name = 'foo';
        $field = 'bar';

        $processor->addField($name, $field);

        $this->assertEquals($field, $processor->getField($name));
        $this->assertContains($field, $processor->getFields());
    }

    public function testCanDetermineIfAllSpecifiedFieldsArePresent()
    {
        $processor = $this->generateDummyClass();

        $request = $this->createMock(ServerRequestInterface::class);

        $fields = [
            'test1' => 'field1',
            'test2' => 'field2',
            'test3' => 'field3'
        ];

        $results = [
            'field1' => (new FieldOperationCacheBuilder)->withSanitizedInputValue('foo'),
            'field3' => (new FieldOperationCacheBuilder)->withSanitizedInputValue('bar'),
        ];

        $processor->setFields($fields);

        $processor->process($request, $results);

        $this->assertFalse($processor->allFieldsPresent);

        $results['field2'] = (new FieldOperationCacheBuilder)->withSanitizedInputValue('baz');

        $processor->process($request, $results);

        $this->assertTrue($processor->allFieldsPresent);
    }

    public function testExtractsValuesAsExpectedEvenIfAllFieldsAreNotPresent()
    {
        $processor = $this->generateDummyClass();

        $request = $this->createMock(ServerRequestInterface::class);

        $fields = [
            'test1' => 'field1',
            'test2' => 'field2',
            'test3' => 'field3'
        ];

        $results = [
            'field1' => (new FieldOperationCacheBuilder)->withSanitizedInputValue('foo'),
            'field2' => (new FieldOperationCacheBuilder)->withSanitizedInputValue('bar'),
            'field3' => (new FieldOperationCacheBuilder)->withSanitizedInputValue('baz'),
        ];

        $expected = [
            'test1' => 'foo',
            'test2' => 'bar',
            'test3' => 'baz'
        ];

        $processor->fields = $fields;

        $processor->process($request, $results);

        $this->assertEquals($expected, $processor->extractedValues);

        unset($results['field2'], $expected['test2']);

        $processor->process($request, $results);

        $this->assertEquals($expected, $processor->extractedValues);
    }

    public function testAccuratelyDeterminesIfAValueHasBeenUpdated()
    {
        $processor = $this->generateDummyClass();

        $request = $this->createMock(ServerRequestInterface::class);

        $fields = [
            'test1' => 'field1',
            'test2' => 'field2',
            'test3' => 'field3'
        ];

        $updated = [
            'field1' => (new FieldOperationCacheBuilder)->withUpdateSuccessful(false),
            'field2' => (new FieldOperationCacheBuilder)->withUpdateSuccessful(true),
            'field3' => (new FieldOperationCacheBuilder)->withUpdateSuccessful(true),
        ];

        $notUpdated = [
            'field1' => (new FieldOperationCacheBuilder)->withUpdateSuccessful(false),
            'field2' => (new FieldOperationCacheBuilder)->withUpdateSuccessful(false),
            'field3' => (new FieldOperationCacheBuilder)->withUpdateSuccessful(false),
        ];

        $processor->fields = $fields;

        $processor->process($request, $updated);

        $this->assertTrue($processor->valueUpdated);

        $processor->process($request, $notUpdated);

        $this->assertFalse($processor->valueUpdated);
    }
}
