<?php

namespace WebTheory\Saveyour\Factories;

use WebTheory\GuctilityBelt\Traits\SmartFactoryTrait;
use WebTheory\Saveyour\Contracts\FieldDataManagerInterface;
use WebTheory\Saveyour\Contracts\FormFieldControllerInterface;
use WebTheory\Saveyour\Contracts\FormFieldInterface;
use WebTheory\Saveyour\Contracts\FieldDataManagerResolverFactoryInterface as iDataManagerFactory;
use WebTheory\Saveyour\Contracts\FormFieldResolverFactoryInterface as iFormFieldFactory;
use WebTheory\Saveyour\Controllers\FormFieldController;

class FieldFactory
{
    use SmartFactoryTrait;

    /**
     * @var iFormFieldFactory
     */
    protected $formFieldFactory;

    /**
     * @var iDataManagerFactory
     */
    protected $dataManagerFactory;

    /**
     *
     */
    protected $controller = FormFieldController::class;

    /**
     *
     */
    public function __construct(iFormFieldFactory $formFieldFactory, iDataManagerFactory $dataManagerFactory, ?string $controller = null)
    {
        $this->formFieldFactory = $formFieldFactory;
        $this->dataManagerFactory = $dataManagerFactory;

        if (isset($controller)) {
            $this->controller = $controller;
        }
    }

    /**
     *
     */
    public function create(array $args): FormFieldControllerInterface
    {
        if ($args['type']) {
            $args['form_field'] = $this->createFormField($args['type']);
        }

        if ($args['data']) {
            $args['data_manager'] = $this->createDataManager($args['data']);
        }

        unset($args['type'], $args['data']);

        return $this->createController($args);
    }

    /**
     *
     */
    protected function createController(array $args): FormFieldControllerInterface
    {
        return $this->build($this->controller, $args);
    }

    /**
     *
     */
    protected function createFormField(array $args): FormFieldInterface
    {
        $type = $args['@create'];
        unset($args['@create']);

        return $this->formFieldFactory->create($type, $args);
    }

    /**
     *
     */
    protected function createDataManager(array $args): FieldDataManagerInterface
    {
        $manager = $args['@create'];
        unset($args['@create']);

        return $this->dataManagerFactory->create($manager, $args);
    }
}
