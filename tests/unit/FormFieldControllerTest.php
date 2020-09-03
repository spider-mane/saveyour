<?php

use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Validator;
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

    /**
     *
     */
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

    // public function testCanSetAndGetFormField()
    // {
    //     $field = $this->createMock(FormFieldInterface::class);

    //     $controller = new FormFieldController('test', $field);
    //     // $controller->setFormField($field);

    //     $this->assertEquals($field, $controller->getFormField());
    // }

    // public function testFormFieldIsProperInterface()
    // {
    //     $this->expectException(TypeError::class);

    //     $controller = new FormFieldController('test', 'dontdoit');
    //     // $field = $this->createMock(FormFieldInterface::class);

    //     // $controller->setFormField(new DateTime);
    //     // $controller->setFormField('dontdoit');

    //     $this->assertEmpty($controller->getFormField());
    // }

    public function testCanConstructWithDataManager()
    {
        $interface = FieldDataManagerInterface::class;

        $dataManager = $this->createMock($interface);

        $controller = new FormFieldController('test', null, $dataManager);

        $this->assertTrue($controller->hasDataManager());
        $this->assertEquals($dataManager, $controller->getDataManager());
    }

    // public function testCanSetAndGetDataManager()
    // {
    //     $interface = FieldDataManagerInterface::class;

    //     $manager = $this->createMock($interface);
    //     $controller = new FormFieldController('test', null, $manager);

    //     // $controller->setDataManager($manager);

    //     $this->assertTrue($controller->hasDataManager());
    //     $this->assertEquals($manager, $controller->getDataManager());
    // }

    // public function testDataManagerIsProperInterface()
    // {
    //     $this->expectException(TypeError::class);

    //     $controller = new FormFieldController('test');

    //     $controller->setDataManager(new DateTime);

    //     $this->assertNull($controller->getDataManager());
    // }

    public function testCanConstructWithAndGetFormatter()
    {
        $interface = DataFormatterInterface::class;

        $formatter = $this->createMock($interface);

        $controller = new FormFieldController('test', null, null, $formatter);

        $this->assertEquals($formatter, $controller->getDataFormatter());
    }

    public function testCanConstructWithAndGetEscaper()
    {
        $escaper = 'strtoupper';
        $controller = new FormFieldController('test', null, null, null, null, null, $escaper);

        // $controller->setEscaper($escaper);

        $this->assertEquals($escaper, $controller->getEscaper());
    }

    // public function testEscaperMustBeCallableOrNull()
    // {
    //     $this->expectException(TypeError::class);

    //     $controller = new FormFieldController('test');

    //     $default = $controller->getEscaper();

    //     $controller->setEscaper('ggfghpap43243');
    //     $controller->setEscaper(5635);
    //     $controller->setEscaper(5354.65563);
    //     $controller->setEscaper(true);
    //     $controller->setEscaper(new DateTime);

    //     $this->assertEquals($default, $controller->getEscaper());

    //     $escaper = 'strtoupper';
    //     $controller->setEscaper($controller);

    //     $this->assertEquals($escaper, $controller->getEscaper());
    //     $this->assertIsCallable($controller->getEscaper());

    //     $controller->setEscaper(null);

    //     $this->assertNull($controller->getEscaper());
    // }

    public function testCanSetAndGetProcessingDisabled()
    {
        $controller = new FormFieldController('test', null, null, null, null, null, null, true);

        // $controller->setProcessingDisabled(true);

        $this->assertTrue($controller->isProcessingDisabled());

        $controller = new FormFieldController('test', null, null, null, null, null, null, false);

        // $controller->setProcessingDisabled(false);

        $this->assertFalse($controller->isProcessingDisabled());
    }

    public function testCanProcessValidInputOnPost()
    {
        $requestVar = 'test';
        $manager = $this->createMock(FieldDataManagerInterface::class);
        $rule = ['phone' => ['validator' => Validator::phone()]];

        // $controller->setDataManager($manager);
        // $controller->addRule('phone', Validator::phone());
        $controller = new FormFieldController($requestVar, null, $manager, null, null, $rule);

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
        $rule = ['phone' => ['validator' => Validator::phone()]];

        // $controller->setDataManager($manager);
        // $controller->addRule('phone', Validator::phone());
        $controller = new FormFieldController($requestVar, null, $manager, null, null, $rule);

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
        $rule = ['phone' => ['validator' => Validator::phone(), 'alert' => 'invalid phone']];

        // $controller->setDataManager($manager);
        // $controller->addRule('phone', Validator::phone(), 'invalid phone');
        $controller = new FormFieldController($requestVar, null, $manager, null, null, $rule);

        $input = 'foobar';

        $request = ServerRequest::fromGlobals()
            ->withMethod('POST')
            ->withParsedBody([$requestVar => $input]);

        $results = $controller->process($request);

        $this->assertFalse($results->sanitizedInputValue());
        $this->assertEquals(false, $results->sanitizedInputValue());
        $this->assertContains('invalid phone', $results->ruleViolations());
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

        $manager = new class implements FieldDataManagerInterface
        {
            public function getCurrentData(ServerRequestInterface $request)
            {
                return 'foobar';
            }

            public function handleSubmittedData(ServerRequestInterface $request, $data): bool
            {
                return true;
            }
        };

        // $controller->setFormField(new Input);
        // $controller->setDataManager($manager);
        $controller = new FormFieldController($name, new Input, $manager);

        $field = $controller->render(ServerRequest::fromGlobals());

        $this->assertEquals($name, $field->getName());
        $this->assertEquals('foobar', $field->getValue());
    }

    // public function testDoesNotEscapeValueIfEscaperSetToNull()
    // {
    //     $request = $this->createMock(ServerRequestInterface::class);

    //     $manager = new class implements FieldDataManagerInterface
    //     {
    //         public function getCurrentData(ServerRequestInterface $request)
    //         {
    //             return '<div>foobar</div>';
    //         }

    //         public function handleSubmittedData(ServerRequestInterface $request, $data): bool
    //         {
    //             return true;
    //         }
    //     };

    //     $escaped = '&lt;div&gt;foobar&lt;/div&gt;';
    //     $unescaped = '<div>foobar</div>';

    //     // $controller->setDataManager($manager);
    //     // $controller->setFormField(new Input);

    //     // $controller->setEscaper('htmlspecialchars');
    //     $controller = new FormFieldController('test', new Input, $manager, null, null, null, 'htmlspecialchars');

    //     $this->assertEquals($escaped, $controller->render($request)->getValue());

    //     $controller = new FormFieldController('test', new Input, $manager, null, null, null, null);

    //     $this->assertEquals($unescaped, $controller->render($request)->getValue());
    // }
}
