<?php

namespace WebTheory\Saveyour\Data;

use WebTheory\Saveyour\Contracts\Data\FieldDataManagerInterface;
use WebTheory\Saveyour\Data\Abstracts\AbstractModelDataManager;
use WebTheory\Saveyour\Data\Abstracts\ManagesModelPropertyTrait;

class ModelPropertyDataManager extends AbstractModelDataManager implements FieldDataManagerInterface
{
    use ManagesModelPropertyTrait;

    public function __construct(object $model, string $property)
    {
        $this->model = $model;
        $this->property = $property;
    }
}
