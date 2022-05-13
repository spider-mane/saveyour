<?php

namespace WebTheory\Saveyour\Data\Abstracts;

use Psr\Http\Message\ServerRequestInterface;
use WebTheory\Saveyour\Contracts\Data\FieldDataManagerInterface;

abstract class AbstractModelDataManager implements FieldDataManagerInterface
{
    protected object $model;

    public function getCurrentData(ServerRequestInterface $request)
    {
        return $this->getModelData($this->model);
    }

    public function handleSubmittedData(ServerRequestInterface $request, $data): bool
    {
        if ($this->getCurrentData($request) === $data) {
            return false;
        }

        $this->setModelData($this->model, $data);

        return true;
    }

    abstract protected function getModelData(object $model);

    abstract protected function setModelData(object $model, $data);
}
