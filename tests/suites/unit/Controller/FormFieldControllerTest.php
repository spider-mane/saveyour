<?php

use Tests\Support\TestCase;
use WebTheory\Saveyour\Contracts\FieldDataManagerInterface;
use WebTheory\Saveyour\Contracts\FormFieldControllerInterface;
use WebTheory\Saveyour\Contracts\FormFieldInterface;
use WebTheory\Saveyour\Contracts\ValidatorInterface;
use WebTheory\Saveyour\Controllers\FormFieldController;

class FormFieldControllerTest extends TestCase
{
    protected FormFieldController $stubController;

    protected FormFieldInterface $stubField;

    protected FieldDataManagerInterface $stubDataManager;

    protected ValidatorInterface $stubValidator;

    protected $baseInterface = FormFieldControllerInterface::class;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stubController = new FormFieldController('test', ['test'], false);
    }
}
