<?php

namespace WebTheory\Saveyour\Factory;

use WebTheory\Factory\Traits\SmartFactoryTrait;
use WebTheory\Saveyour\Contracts\Controller\FormFieldControllerInterface;
use WebTheory\Saveyour\Contracts\Data\FieldDataManagerInterface;
use WebTheory\Saveyour\Contracts\Factory\FieldDataManagerResolverFactoryInterface as iDataManagerFactory;
use WebTheory\Saveyour\Contracts\Factory\FormFieldResolverFactoryInterface as iFormFieldFactory;
use WebTheory\Saveyour\Contracts\Field\FormFieldInterface;
use WebTheory\Saveyour\Controller\FormFieldController;

class FieldFactory
{
    use SmartFactoryTrait;

    protected iFormFieldFactory $formFieldFactory;

    protected iDataManagerFactory $dataManagerFactory;

    protected $controller = FormFieldController::class;

    public function __construct(iFormFieldFactory $formFieldFactory, iDataManagerFactory $dataManagerFactory, ?string $controller = null)
    {
        $this->formFieldFactory = $formFieldFactory;
        $this->dataManagerFactory = $dataManagerFactory;

        if (isset($controller)) {
            $this->controller = $controller;
        }
    }

    public function create(array $args): FormFieldControllerInterface
    {
        if (isset($args['type'])) {
            $args['form_field'] = $this->createFormField($args['type']);
        }

        if (isset($args['data'])) {
            $args['data_manager'] = $this->createDataManager($args['data']);
        }

        unset($args['type'], $args['data']);

        return $this->createController($args);
    }

    protected function createController(array $args): FormFieldControllerInterface
    {
        return $this->build($this->controller, $args);
    }

    protected function createFormField(array $args): FormFieldInterface
    {
        $type = $args['@create'];
        unset($args['@create']);

        return $this->formFieldFactory->create($type, $args);
    }

    protected function createDataManager(array $args): FieldDataManagerInterface
    {
        $manager = $args['@create'];
        unset($args['@create']);

        return $this->dataManagerFactory->create($manager, $args);
    }

    public function __call($name, $args)
    {
        $args = $args[0];
        $args['type']['@create'] = $this->getArg($name);

        return $this->create($args);
    }
}
