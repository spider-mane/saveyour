<?php

namespace Tests\Suites\Unit\Controller\Builder;

use Psr\Http\Message\ServerRequestInterface;
use Tests\Support\UnitTestCase;
use WebTheory\Saveyour\Contracts\Data\FieldDataManagerInterface;
use WebTheory\Saveyour\Contracts\Field\FormFieldInterface;
use WebTheory\Saveyour\Contracts\Formatting\DataFormatterInterface;
use WebTheory\Saveyour\Contracts\Validation\ValidatorInterface;
use WebTheory\Saveyour\Controller\Builder\FormFieldControllerBuilder;
use WebTheory\Saveyour\Controller\FormFieldController;

class FormFieldControllerBuilderTest extends UnitTestCase
{
    protected FormFieldControllerBuilder $sut;

    protected FormFieldInterface $mockFormField;

    protected FieldDataManagerInterface $mockDataManager;

    protected ValidatorInterface $mockValidator;

    protected DataFormatterInterface $mockDataFormatter;

    protected ServerRequestInterface $mockRequest;

    protected string $dummyRequestVar;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dummyRequestVar = $this->fake->slug;
        $this->mockRequest = $this->createMock(ServerRequestInterface::class);
        $this->mockFormField = $this->createMock(FormFieldInterface::class);
        $this->mockDataManager = $this->createMock(FieldDataManagerInterface::class);
        $this->mockValidator = $this->createMock(ValidatorInterface::class);
        $this->mockDataFormatter = $this->createMock(DataFormatterInterface::class);

        $this->sut = new FormFieldControllerBuilder($this->dummyRequestVar);
    }

    /**
     * @test
     */
    public function it_builds_a_fully_configured_of_FormFieldController()
    {
        # Arrange
        $canProcess = $this->fake->boolean;
        $await = $this->dummyList(fn () => $this->unique->slug);

        $sut = new FormFieldControllerBuilder();
        $sut->requestVar($this->dummyRequestVar);
        $sut->formField($this->mockFormField);
        $sut->dataManager($this->mockDataManager);
        $sut->validator($this->mockValidator);
        $sut->formatter($this->mockDataFormatter);
        $sut->canProcess($canProcess);
        $sut->awaitAll($await);

        # Expect
        $this->mockFormField->expects($this->atLeastOnce())
            ->method('setName');

        $this->mockDataManager->expects($this->atLeastOnce())
            ->method('getCurrentData');

        $this->mockValidator->expects($this->atLeastOnce())
            ->method('validate');

        $this->mockDataFormatter->expects($this->atLeastOnce())
            ->method('formatData');

        # Act
        $controller = $sut->get();
        $controller->render($this->mockRequest);
        $controller->process($this->mockRequest);
        $controller->validate($this->fake->sentence);

        # Assert
        $this->assertInstanceOf(FormFieldController::class, $controller);
        $this->assertSame($this->dummyRequestVar, $controller->getRequestVar());
        $this->assertSame($canProcess, $controller->isPermittedToProcess());
        $this->assertSame($await, $controller->mustAwait());
    }

    /**
     * @test
     */
    public function it_creates_instance_using_for_static_method()
    {
        # Arrange
        $sut = FormFieldControllerBuilder::for($this->dummyRequestVar);

        # Act
        $result = $sut->get();

        # Assert
        $this->assertSame($this->dummyRequestVar, $result->getRequestVar());
    }

    /**
     * @test
     * @dataProvider configurationDataProvider
     */
    public function it_builds_a_FormField_instance_with_limited_configuration(string $method, $arg)
    {
        # Arrange
        $this->sut->$method($arg);

        # Act
        $result = $this->sut->get();

        # Assert
        $this->assertInstanceOf(FormFieldController::class, $result);
    }

    public function configurationDataProvider(): array
    {
        return [
            'form field only' => [
                'method' => 'formField',
                'arg' => $this->createMock(FormFieldInterface::class),
            ],
            'data manager only' => [
                'method' => 'dataManager',
                'arg' => $this->createMock(FieldDataManagerInterface::class),
            ],
            'validator only' => [
                'method' => 'validator',
                'arg' => $this->createMock(ValidatorInterface::class),
            ],
            'formatter only' => [
                'method' => 'formatter',
                'arg' => $this->createMock(DataFormatterInterface::class),
            ],
        ];
    }

    /**
     * @test
     * @dataProvider methodChainingDataProvider
     */
    public function it_returns_self_when_build_designated_method_is_called(string $method, $arg)
    {
        $this->assertSame($this->sut, $this->sut->$method($arg));
    }

    public function methodChainingDataProvider(): array
    {
        $this->initFaker();

        return [
            'requestVar' => [
                'method' => 'requestVar',
                'arg' => $this->fake->slug,
            ],
            'formField' => [
                'method' => 'formField',
                'arg' => $this->createMock(FormFieldInterface::class),
            ],
            'dataManager' => [
                'method' => 'dataManager',
                'arg' => $this->createMock(FieldDataManagerInterface::class),
            ],
            'validator' => [
                'method' => 'validator',
                'arg' => $this->createMock(ValidatorInterface::class),
            ],
            'formatter' => [
                'method' => 'formatter',
                'arg' => $this->createMock(DataFormatterInterface::class),
            ],
            'canProcess' => [
                'method' => 'canProcess',
                'arg' => $this->fake->boolean,
            ],
            'awaitAll' => [
                'method' => 'awaitAll',
                'arg' => $this->dummyList(fn () => $this->unique->slug, 3),
            ],
            'await' => [
                'method' => 'await',
                'arg' => $this->fake->slug,
            ],
        ];
    }

    /**
     * @test
     * @dataProvider appendedAwaitDataProvider
     */
    public function it_appends_to_current_await_list_when_dedicated_method_is_called(string $method, $append)
    {
        # Arrange
        $start = $this->dummyList(fn () => $this->unique->slug, 3);
        $final = [...is_array($append) ? $append : [$append], ...$start];

        $this->sut->awaitAll($start);

        # Act
        $this->sut->$method($append);
        $result = $this->sut->get()->mustAwait();

        # Assert
        $this->assertEqualsCanonicalizing($final, $result);
    }

    public function appendedAwaitDataProvider(): array
    {
        $this->initFaker();

        return [
            'await' => [
                'method' => 'await',
                'append' => $this->unique->slug,
            ],
            'awaitAll' => [
                'method' => 'awaitAll',
                'append' => $this->dummyList(fn () => $this->unique->slug, 2),
            ],
        ];
    }

    /**
     * @test
     */
    public function it_can_be_instantiated_without_arguments()
    {
        $this->assertInstanceOf(
            FormFieldControllerBuilder::class,
            new FormFieldControllerBuilder()
        );
    }
}
