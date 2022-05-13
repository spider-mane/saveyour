<?php

namespace WebTheory\Saveyour\Data\Factory;

use WebTheory\Saveyour\Data\QueriedModelPropertyDataManager;
use WebTheory\Saveyour\Enum\ServerRequestLocation;

class QueriedModelPropertyDataManagerFactory
{
    protected object $repository;

    protected string $queryMethod;

    protected ?string $updateMethod = null;

    protected ?string $commitMethod = null;

    protected string $lookup;

    protected ServerRequestLocation $location;

    public function __construct(
        object $repository,
        string $queryMethod,
        string $lookup,
        ?ServerRequestLocation $location = null,
        ?string $updateMethod = null,
        ?string $commitMethod = null
    ) {
        $this->repository = $repository;
        $this->queryMethod = $queryMethod;
        $this->lookup = $lookup;
        $this->location = $location ?? ServerRequestLocation::Attribute();
        $this->updateMethod = $updateMethod;
        $this->commitMethod = $commitMethod;
    }

    public function create(string $property): QueriedModelPropertyDataManager
    {
        return new QueriedModelPropertyDataManager(
            $property,
            $this->repository,
            $this->queryMethod,
            $this->lookup,
            $this->location,
            $this->updateMethod,
            $this->commitMethod
        );
    }
}
