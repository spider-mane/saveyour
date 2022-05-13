<?php

namespace WebTheory\Saveyour\Data;

use WebTheory\Saveyour\Contracts\Data\FieldDataManagerInterface;
use WebTheory\Saveyour\Data\Abstracts\AbstractModelDataManager;
use WebTheory\Saveyour\Data\Abstracts\ManagesModelTrait;

class ModelDataManager extends AbstractModelDataManager implements FieldDataManagerInterface
{
    use ManagesModelTrait;

    public function __construct(object $model, string $getMethod, string $setMethod)
    {
        $this->model = $model;
        $this->getMethod = $getMethod;
        $this->setMethod = $setMethod;
    }
}
