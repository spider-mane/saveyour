<?php

use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use WebTheory\Saveyour\Contracts\FieldOperationCacheInterface;
use WebTheory\Saveyour\Contracts\FormDataProcessingCacheInterface;
use WebTheory\Saveyour\Contracts\FormDataProcessorInterface;
use WebTheory\Saveyour\Contracts\FormFieldControllerInterface;
use WebTheory\Saveyour\Contracts\FormValidatorInterface;
use WebTheory\Saveyour\Controllers\BaseFormFieldController;
use WebTheory\Saveyour\Controllers\FieldOperationCache;
use WebTheory\Saveyour\Controllers\FieldOperationCacheBuilder;
use WebTheory\Saveyour\Controllers\FormFieldController;
use WebTheory\Saveyour\Controllers\FormFieldControllerBuilder;
use WebTheory\Saveyour\Controllers\FormSubmissionManager;

class FormSubmissionManagerTest extends TestCase
{
    /**
     * @var FormSubmissionManager
     */
    public $testManager;

    /**
     * @var FormValidatorInterface
     */
    public $testFormValidator;

    public function setup(): void
    {
        $this->testManager = new FormSubmissionManager;
        $this->testFormValidator = $this->createMock(FormValidatorInterface::class);
    }

    protected function generatePassingValidator()
    {
        return new class implements FormValidatorInterface
        {
            public function isValid(ServerRequestInterface $request): bool
            {
                return true;
            }
        };
    }

    protected function generateFailingValidator()
    {
        return new class implements FormValidatorInterface
        {
            public function isValid(ServerRequestInterface $request): bool
            {
                return false;
            }
        };
    }

    public function testAppropriateMethodsAreChainable()
    {
        $manager = (new FormSubmissionManager)
            ->setValidators(['test' => $this->testFormValidator])
            ->addValidator('test', $this->testFormValidator);

        $this->assertInstanceOf(FormSubmissionManager::class, $manager);
    }

    public function testCanSetAndGetMultipleValidators()
    {
        $manager = new FormSubmissionManager;

        $validators = [
            $this->testFormValidator,
            $this->createMock(FormValidatorInterface::class),
            $this->createMock(FormValidatorInterface::class)
        ];

        $manager->setValidators($validators);

        $this->assertCount(3, $manager->getValidators());
    }

    public function testCanGetAndSetSingleValidators()
    {
        $manager = new FormSubmissionManager;
        $manager->addValidator('test', $this->testFormValidator);

        $validator = $manager->getValidators();
        $this->assertEquals($this->testFormValidator, $validator['test']);
    }

    public function testValidatorsMustBeCorrectInterface()
    {
        $this->expectException(TypeError::class);

        $manager = $this->testManager;
        $manager->addValidator('fail', new DateTime);

        $this->assertEmpty($manager->getValidators()[0]);
        $this->assertCount(0, $manager->getValidators());
    }

    public function testCanSetAndGetAlerts()
    {
        $manager = new FormSubmissionManager;

        $alerts = [
            '1' => 'foo',
            '2' => 'bar'
        ];

        $manager->setAlerts($alerts);

        $this->assertEquals($alerts, $manager->getAlerts());
        $this->assertCount(count($alerts), $manager->getAlerts());
    }

    public function testCanAddAndGetSingleAlert()
    {
        $manager = new FormSubmissionManager;

        $rule = 'foo';
        $alert = 'bar';

        $manager->addAlert($rule, $alert);

        $this->assertEquals($alert, $manager->getAlert($rule));
    }

    public function testCanSetAlertsViaValidatorSetterMethod()
    {
        $manager = new FormSubmissionManager;

        $alerts = [
            'test1' => 'foo',
            'test2' => 'bar',
        ];

        $verification = [
            'test1' => [
                'validator' => $this->createMock(FormValidatorInterface::class),
                'alert' => $alerts['test1']
            ],
            'test2' => [
                'validator' => $this->createMock(FormValidatorInterface::class),
                'alert' => $alerts['test2']
            ]
        ];

        $manager->setValidators($verification);

        $this->assertEquals($alerts, $manager->getAlerts());
    }

    public function testCanAddSingleAlertViaValidatorAdderMethod()
    {
        $manager = new FormSubmissionManager;

        $rule = 'foo';
        $alert = 'bar';

        $manager->addValidator($rule, $this->createMock(FormValidatorInterface::class), $alert);

        $this->assertEquals($alert, $manager->getAlert($rule));
    }

    public function testCanSetAndGetFormFieldControllers()
    {
        $manager  = new FormSubmissionManager;

        $fields = [
            new FormFieldController('test1'),
            new FormFieldController('test2'),
            new FormFieldController('test3'),
            // $this->createMock(FormFieldControllerInterface::class),
            // $this->createMock(FormFieldControllerInterface::class),
            // $this->createMock(FormFieldControllerInterface::class),
        ];

        $manager->setFields(...$fields);

        $this->assertEquals($fields, $manager->getFields());
    }

    public function testCanAddSingleFormFieldController()
    {
        $manager = new FormSubmissionManager;

        $field1 = new FormFieldController('test1');
        $field2 = new FormFieldController('test2');

        $manager->addField($field1);
        $manager->addField($field2);

        $this->assertCount(2, $manager->getFields());
        $this->assertContains($field1, $manager->getFields());
        $this->assertContains($field2, $manager->getFields());
    }

    public function testCanSetAndGetProcessors()
    {
        $manager = new FormSubmissionManager;

        $processors = [
            $this->createMock(FormDataProcessorInterface::class),
            $this->createMock(FormDataProcessorInterface::class),
            $this->createMock(FormDataProcessorInterface::class),
        ];

        $manager->setProcessors(...$processors);

        $this->assertEquals($processors, $manager->getProcessors());
    }

    public function testCanAddSingleProcessor()
    {
        $manager = new FormSubmissionManager;

        $processor1 = $this->createMock(FormDataProcessorInterface::class);
        $processor2 = $this->createMock(FormDataProcessorInterface::class);

        $manager->addProcessor($processor1);
        $manager->addProcessor($processor2);

        $this->assertCount(2, $manager->getProcessors());
        $this->assertContains($processor1, $manager->getProcessors());
        $this->assertContains($processor2, $manager->getProcessors());
    }

    public function testWontRunIfValidationFails()
    {
        $manager = new FormSubmissionManager;

        $manager->addValidator('fail', $this->generateFailingValidator());
        $manager->addValidator('pass', $this->generatePassingValidator());

        $results = $manager->process(ServerRequest::fromGlobals());

        $this->assertCount(1, $results->requestViolations());
        $this->assertEmpty($results->inputResults());
    }

    public function testPassesServerRequestToFormFieldControllers()
    {
        $manager = new FormSubmissionManager;

        $param = 'test';

        $controller = new class ($param) extends FormFieldController implements FormFieldControllerInterface
        {
            public $request;

            public function process(ServerRequestInterface $request): FieldOperationCacheInterface
            {
                $this->request = $request;

                return new FieldOperationCacheBuilder;
            }
        };

        $manager->addField($controller);

        $request = new ServerRequest('post', 'https://test.test');
        $request = $request->withParsedBody([$param => 'test-param']);

        $manager->process($request);

        $this->assertEquals($request, $controller->request);
    }

    public function testPassesFieldOperationCacheObjectsToProcessors()
    {
        $manager = new FormSubmissionManager;

        $fields = ['test1', 'test2', 'test3'];
        $invalid = ['fail1', 'fail2'];

        $processor = new class ($fields) implements FormDataProcessorInterface
        {
            public $fields;
            public $results;

            public function __construct(array $fields)
            {
                $this->fields = $fields;
            }

            public function process(ServerRequestInterface $request, array $results): ?FormDataProcessingCacheInterface
            {
                $this->results = $results;

                return null;
            }

            public function getFields(): array
            {
                return $this->fields;
            }
        };

        $formFields = [
            new FormFieldController($fields[0]),
            new FormFieldController($fields[1]),
            new FormFieldController($fields[2]),
            new FormFieldController($invalid[0]),
            new FormFieldController($invalid[1]),
        ];

        $manager->setFields(...$formFields);
        $manager->addProcessor($processor);

        $request = ServerRequest::fromGlobals()->withQueryParams([
            $fields[0] => 'foo',
            $fields[1] => 'bar',
            $fields[2] => 'baz',
            $invalid[0] => 'doe',
            $invalid[1] => 'rae',
        ]);

        $results = $manager->process($request)->inputResults();

        $this->assertCount(count($fields) + count($invalid), $results);
        $this->assertCount(count($fields), $processor->results);

        foreach ($invalid as $thing) {
            unset($results[$thing]);
        }

        $this->assertEquals($results, $processor->results);
    }

    public function testProcessesDependentFieldsAfterDependencies()
    {
        $field1 = new FormFieldControllerBuilder('field-1');
        $field2 = new FormFieldControllerBuilder('field-2');
        $field3 = new FormFieldControllerBuilder('field-3');
        $field4 = new FormFieldControllerBuilder('field-4');
        $field5 = new FormFieldControllerBuilder('field-5');

        $field1->await('field-3');
        $field3->await('field-4')->await('field-5');
        $field5->await('field-2');

        $field1 = $field1->create();
        $field2 = $field2->create();
        $field3 = $field3->create();
        $field4 = $field4->create();
        $field5 = $field5->create();

        $fields = [$field1, $field2, $field3, $field4, $field5];

        $request = new ServerRequest('post', 'https://test.test');
        $request = $request->withParsedBody([
            'field-1' => '1',
            'field-2' => '2',
            'field-3' => '3',
            'field-4' => '4',
            'field-5' => '5',
        ]);

        $manager = new FormSubmissionManager;
        $manager->setFields(...$fields);

        $results = $manager->process($request);

        $processed = array_keys($results->inputResults());

        $field1Position = array_search('field-1', $processed);
        $field2Position = array_search('field-2', $processed);
        $field3Position = array_search('field-3', $processed);
        $field4Position = array_search('field-4', $processed);
        $field5Position = array_search('field-5', $processed);

        $this->assertGreaterThan($field3Position, $field1Position);
        $this->assertGreaterThan($field4Position, $field3Position);
        $this->assertGreaterThan($field5Position, $field3Position);
        $this->assertGreaterThan($field2Position, $field5Position);
    }
}
