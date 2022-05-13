<?php

namespace WebTheory\Saveyour\Data\Factory;

use WebTheory\Saveyour\Data\ModelPropertyDataManager;

class ModelPropertyDataManagerFactory
{
    protected object $model;

    public function __construct(object $model)
    {
        $this->model = $model;
    }

    public function create(string $property): ModelPropertyDataManager
    {
        return new ModelPropertyDataManager($this->model, $property);
    }
}
