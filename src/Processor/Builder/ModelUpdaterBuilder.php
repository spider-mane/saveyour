<?php

namespace WebTheory\Saveyour\Processor\Builder;

use WebTheory\Saveyour\Processor\Builder\Abstracts\AbstractModelUpdaterBuilder;
use WebTheory\Saveyour\Processor\ModelUpdater;

class ModelUpdaterBuilder extends AbstractModelUpdaterBuilder
{
    protected object $model;

    public function withModel(object $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function build(): ModelUpdater
    {
        return new ModelUpdater(
            $this->name,
            $this->fields,
            $this->model,
            $this->repository,
            $this->updateMethod,
            $this->commitMethod,
        );
    }
}
