<?php

namespace WebTheory\Saveyour\Factory;

use WebTheory\Saveyour\Contracts\FieldDataManagerResolverFactoryInterface;
use WebTheory\Saveyour\Contracts\FormFieldResolverFactoryInterface;

class SimpleFieldFactory extends FieldFactory
{
    public function __construct(array $options = [])
    {
        $field = $options['form_field_factory'] ?? [];
        $manager = $options['data_manager_factory'] ?? [];
        $controller = $options['controller'] ?? null;

        $this->formFieldFactory = $this->createFormFieldFactory($field);
        $this->dataManagerFactory = $this->createDataManagerFactory($manager);

        if (isset($controller)) {
            $this->controller = $controller;
        }
    }

    protected function createFormFieldFactory(array $options): FormFieldResolverFactoryInterface
    {
        $namespaces = $options['namespaces'] ?? [];
        $fields = $options['fields'] ?? [];

        return new FormFieldFactory($namespaces, $fields);
    }

    protected function createDataManagerFactory(array $options): FieldDataManagerResolverFactoryInterface
    {
        $namespaces = $options['namespaces'] ?? [];
        $managers = $options['managers'] ?? [];

        return new DataManagerFactory($namespaces, $managers);
    }
}
