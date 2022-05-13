<?php

namespace WebTheory\Saveyour\Data;

use WebTheory\Saveyour\Abstracts\QueriesRepositoryTrait;
use WebTheory\Saveyour\Contracts\Data\FieldDataManagerInterface;
use WebTheory\Saveyour\Data\Abstracts\AbstractQueriedModelDataManager;
use WebTheory\Saveyour\Data\Abstracts\ManagesModelTrait;
use WebTheory\Saveyour\Enum\ServerRequestLocation;

class QueriedModelDataManager extends AbstractQueriedModelDataManager implements FieldDataManagerInterface
{
    use ManagesModelTrait;
    use QueriesRepositoryTrait;

    public function __construct(
        object $repository,
        string $queryMethod,
        string $getMethod,
        string $setMethod,
        string $lookup,
        ?ServerRequestLocation $location = null,
        ?string $updateMethod = null
    ) {
        $this->repository = $repository;
        $this->queryMethod = $queryMethod;
        $this->getMethod = $getMethod;
        $this->setMethod = $setMethod;
        $this->lookup = $lookup;
        $this->location = $location ?? ServerRequestLocation::Attribute();
        $this->updateMethod = $updateMethod;
    }
}
