<?php

use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Validator;
use Tests\Support\TestCase;
use WebTheory\Saveyour\Contracts\DataFormatterInterface;
use WebTheory\Saveyour\Contracts\FieldDataManagerInterface;
use WebTheory\Saveyour\Contracts\FieldOperationCacheInterface;
use WebTheory\Saveyour\Contracts\FormFieldControllerInterface;
use WebTheory\Saveyour\Contracts\FormFieldInterface;
use WebTheory\Saveyour\Controllers\FormFieldController;
use WebTheory\Saveyour\Fields\Input;

class FormFieldControllerTest extends TestCase
{
    /**
     * @var FormFieldController
     */
    public $testBase;

    public $baseInterface = FormFieldControllerInterface::class;

    public function testCanGetRequestVar()
    {
        $var = 'thingy';

        $controller = new FormFieldController($var);

        $this->assertEquals($var, $controller->getRequestVar());
    }

    public function testCanConstructWithFormField()
    {
        $interface = FormFieldInterface::class;

        $formField = $this->createMock($interface);

        $controller = new FormFieldController('test', $formField);

        $this->assertEquals($formField, $controller->getFormField());
    }

    public function testCanConstructWithDataManager()
    {
        $interface = FieldDataManagerInterface::class;

        $dataManager = $this->createMock($interface);

        $controller = new FormFieldController('test', null, $dataManager);

        $this->assertTrue($controller->hasDataManager());
        $this->assertEquals($dataManager, $controller->getDataManager());
    }

    public function testCanConstructWithAndGetFormatter()
    {
        $interface = DataFormatterInterface::class;

        $formatter = $this->createMock($interface);

        $controller = new FormFieldController('test', null, null, null, $formatter);

        $this->assertEquals($formatter, $controller->getDataFormatter());
    }

    public function testCanConstructWithAndGetEscaper()
    {
        $escaper = 'strtoupper';
        $controller = new FormFieldController('test', null, null, null, null, null, null, null, $escaper);

        $this->assertEquals($escaper, $controller->getEscaper());
    }

    public function testCanConstructWithAndGetProcessingDisabled()
    {
        $controller = new FormFieldController('test', null, null, null, null, null, true);

        $this->assertTrue($controller->isProcessingDisabled());

        $controller = new FormFieldController('test', null, null, null, null, null, false);

        $this->assertFalse($controller->isProcessingDisabled());
    }

    public function testCanProcessValidInputOnPost()
    {
        $requestVar = 'test';
        $manager = $this->createMock(FieldDataManagerInterface::class);
        $rule = Validator::phone();

        $controller = new FormFieldController($requestVar, null, $manager, $rule);

        $input = '202-561-4684';

        $request = ServerRequest::fromGlobals()
            ->withMethod('POST')
            ->withParsedBody([$requestVar => $input]);

        $results = $controller->process($request);

        $this->assertInstanceOf(FieldOperationCacheInterface::class, $results);
        $this->assertEquals($input, $results->sanitizedInputValue());
    }

    public function testCanProcessValidInputOnGet()
    {
        $requestVar = 'test';
        $manager = $this->createMock(FieldDataManagerInterface::class);
        $rule = Validator::phone();

        $controller = new FormFieldController($requestVar, null, $manager, $rule);

        $input = '202-561-4684';

        $request = ServerRequest::fromGlobals()
            ->withMethod('GET')
            ->withQueryParams([$requestVar => $input]);

        $results = $controller->process($request);

        $this->assertInstanceOf(FieldOperationCacheInterface::class, $results);
        $this->assertEquals($input, $results->sanitizedInputValue());
    }

    public function testRejectsInvalidInput()
    {
        $requestVar = 'test';
        $controller = new FormFieldController($requestVar);
        $manager = $this->createMock(FieldDataManagerInterface::class);
        $rule = Validator::phone()->setTemplate('{{input}} is an invalid phone number');

        $controller = new FormFieldController($requestVar, null, $manager, $rule);

        $input = 'foobar';

        $request = ServerRequest::fromGlobals()
            ->withMethod('POST')
            ->withParsedBody([$requestVar => $input]);

        $results = $controller->process($request);
        $message = "\"{$input}\" is an invalid phone number";

        $this->assertFalse($results->sanitizedInputValue());
        $this->assertEquals(false, $results->sanitizedInputValue());
        $this->assertContains($message, $results->ruleViolations());
    }

    // public function testDoesNotProcessIfVarNotPresent()
    // {
    //     $requestVar = 'test';
    //     $controller = new FormFieldController($requestVar);
    //     $manager = $this->createMock(FieldDataManagerInterface::class);

    //     $controller->setDataManager($manager);
    //     $controller->addRule('phone', Validator::phone());

    //     $request = ServerRequest::fromGlobals()->withMethod('POST');

    //     $results = $controller->process($request);

    //     $this->assertFalse($results->requestVarPresent());
    //     $this->assertNull($results->sanitizedInputValue());
    //     $this->assertFalse($results->updateAttempted());
    //     $this->assertFalse($results->updateSuccessful());
    //     $this->assertEmpty($results->ruleViolations());
    // }

    public function testCanRenderFormField()
    {
        $name = 'test';

        $manager = new class () implements FieldDataManagerInterface {
            public function getCurrentData(ServerRequestInterface $request)
            {
                return 'foobar';
            }

            public function handleSubmittedData(ServerRequestInterface $request, $data): bool
            {
                return true;
            }
        };

        $controller = new FormFieldController($name, new Input(), $manager);

        $field = $controller->render(ServerRequest::fromGlobals());

        $this->assertEquals($name, $field->getName());
        $this->assertEquals('foobar', $field->getValue());
    }
}
