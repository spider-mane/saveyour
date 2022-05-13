<?php

namespace WebTheory\Saveyour\Data\Abstracts;

use Psr\Http\Message\ServerRequestInterface;
use WebTheory\Saveyour\Contracts\Data\FieldDataManagerInterface;

abstract class AbstractQueriedModelDataManager implements FieldDataManagerInterface
{
    public function getCurrentData(ServerRequestInterface $request)
    {
        return $this->getModelData($this->getModelFromRepository($request));
    }

    public function handleSubmittedData(ServerRequestInterface $request, $data): bool
    {
        $model = $this->getModelFromRepository($request);

        if ($this->getModelData($model) === $data) {
            return false;
        }

        $this->setModelData($model, $data);
        $this->maybeUpdateRepository($model);
        $this->maybeCommitRepositoryChanges();

        return true;
    }

    abstract protected function getModelFromRepository(ServerRequestInterface $request);

    abstract protected function getModelData(object $model);

    abstract protected function setModelData(object $model, $data);

    abstract protected function maybeUpdateRepository(object $model);

    abstract protected function maybeCommitRepositoryChanges();
}
