<?php

namespace WebTheory\Saveyour\Processor\Builder;

use WebTheory\Saveyour\Processor\Builder\Abstracts\AbstractModelUpdaterBuilder;
use WebTheory\Saveyour\Processor\ModelUpdater;

class ModelUpdaterBuilder extends AbstractModelUpdaterBuilder
{
    protected object $model;

    /**
     * @return $this
     */
    public function withModel(object $model): ModelUpdaterBuilder
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
