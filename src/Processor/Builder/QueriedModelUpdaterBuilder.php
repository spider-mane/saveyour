<?php

namespace WebTheory\Saveyour\Processor\Builder;

use WebTheory\Saveyour\Enum\ServerRequestLocation;
use WebTheory\Saveyour\Processor\Builder\Abstracts\AbstractModelUpdaterBuilder;
use WebTheory\Saveyour\Processor\QueriedModelUpdater;

class QueriedModelUpdaterBuilder extends AbstractModelUpdaterBuilder
{
    /**
     * Method on the repository to query for the model
     */
    protected string $queryMethod;

    /**
     * Parameter name to on the request that contains repository query data
     */
    protected string $lookup;

    /**
     * Place to look for repository query data in the request
     */
    protected ServerRequestLocation $location;

    public function withQueryMethod(string $queryMethod): self
    {
        $this->queryMethod = $queryMethod;

        return $this;
    }

    public function withLookup(string $lookup): self
    {
        $this->lookup = $lookup;

        return $this;
    }

    public function withLocation(?ServerRequestLocation $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function build(): QueriedModelUpdater
    {
        return new QueriedModelUpdater(
            $this->name,
            $this->fields,
            $this->repository,
            $this->queryMethod,
            $this->updateMethod,
            $this->lookup,
            $this->location,
            $this->commitMethod,
        );
    }
}
