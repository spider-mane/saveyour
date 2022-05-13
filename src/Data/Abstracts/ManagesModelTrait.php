<?php

namespace WebTheory\Saveyour\Data\Abstracts;

trait ManagesModelTrait
{
    /**
     * Method on queried model to get existing data
     */
    protected string $getMethod;

    /**
     * Method on queried model to set new data
     */
    protected string $setMethod;

    protected function getModelData(object $model): ?object
    {
        return $model->{$this->getMethod}();
    }

    protected function setModelData(object $model, $data): void
    {
        $model->{$this->setMethod}($data);
    }
}
