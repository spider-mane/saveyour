<?php

namespace WebTheory\Saveyour\Data;

use WebTheory\Saveyour\Abstracts\QueriesRepositoryTrait;
use WebTheory\Saveyour\Contracts\Data\FieldDataManagerInterface;
use WebTheory\Saveyour\Data\Abstracts\AbstractQueriedModelDataManager;
use WebTheory\Saveyour\Data\Abstracts\ManagesModelPropertyTrait;
use WebTheory\Saveyour\Enum\ServerRequestLocation;

class QueriedModelPropertyDataManager extends AbstractQueriedModelDataManager implements FieldDataManagerInterface
{
    use ManagesModelPropertyTrait;
    use QueriesRepositoryTrait;

    public function __construct(
        string $property,
        object $repository,
        string $queryMethod,
        string $lookup,
        ?ServerRequestLocation $location = null,
        ?string $updateMethod = null,
        ?string $commitMethod = null
    ) {
        $this->repository = $repository;
        $this->property = $property;
        $this->queryMethod = $queryMethod;
        $this->lookup = $lookup;
        $this->location = $location ?? ServerRequestLocation::Attribute();
        $this->updateMethod = $updateMethod;
        $this->commitMethod = $commitMethod;
    }
}
