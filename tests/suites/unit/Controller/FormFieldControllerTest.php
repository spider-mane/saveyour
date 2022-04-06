<?php

use Tests\Support\TestCase;
use WebTheory\Saveyour\Contracts\DataFormatterInterface;
use WebTheory\Saveyour\Contracts\FieldDataManagerInterface;
use WebTheory\Saveyour\Contracts\FormFieldControllerInterface;
use WebTheory\Saveyour\Contracts\FormFieldInterface;
use WebTheory\Saveyour\Contracts\ValidatorInterface;
use WebTheory\Saveyour\Controllers\FormFieldController;

class FormFieldControllerTest extends TestCase
{
    protected FormFieldController $controller;

    protected FormFieldInterface $stubField;

    protected FieldDataManagerInterface $stubDataManager;

    protected ValidatorInterface $stubValidator;

    protected DataFormatterInterface $stubDataFormatter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stubField = $this->createStub(FormFieldInterface::class);
        $this->stubDataManager = $this->createStub(FieldDataManagerInterface::class);
        $this->stubValidator = $this->createStub(ValidatorInterface::class);
        $this->stubDataFormatter = $this->createStub(DataFormatterInterface::class);

        $this->controller = new FormFieldController(
            $this->faker->slug,
            $this->stubField,
            $this->stubDataManager,
            $this->stubValidator,
            $this->stubDataFormatter
        );
    }

    /**
     * @test
     */
    public function it_is_instance_of_FormFieldControllerInterface()
    {
        $this->assertInstanceOf(FormFieldControllerInterface::class, $this->controller);
    }

    /**
     * @test
     */
    public function it_processes()
    {
        $this->markTestIncomplete('This test has not been implemented yet.');
    }
}
