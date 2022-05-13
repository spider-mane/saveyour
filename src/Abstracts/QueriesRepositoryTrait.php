<?php

namespace WebTheory\Saveyour\Abstracts;

use Psr\Http\Message\ServerRequestInterface;
use WebTheory\Saveyour\Enum\ServerRequestLocation;

trait QueriesRepositoryTrait
{
    /**
     * Instance of model repository
     */
    protected object $repository;

    /**
     * Method on the repository to query for the model
     */
    protected string $queryMethod;

    /**
     * Optional method for updating the model in the repository
     */
    protected ?string $updateMethod = null;

    /**
     * Optional method for committing repository changes to database
     */
    protected ?string $commitMethod = null;

    /**
     * Parameter name to on the request that contains repository query data
     */
    protected string $lookup;

    /**
     * Place to look for repository query data in the request
     */
    protected ServerRequestLocation $location;

    protected function getModelFromRepository(ServerRequestInterface $request): ?object
    {
        return $this->repository->{$this->queryMethod}(
            $this->location->lookup($this->lookup, $request)
        );
    }

    protected function maybeUpdateRepository(object $model): void
    {
        if ($this->updateMethod) {
            $this->updateRepository($model);
        }
    }

    protected function maybeCommitRepositoryChanges(): void
    {
        if ($this->commitMethod) {
            $this->commitRepositoryChanges();
        }
    }

    protected function updateRepository(object $model): void
    {
        $this->repository->{$this->updateMethod}($model);
    }

    protected function commitRepositoryChanges(): void
    {
        $this->repository->{$this->commitMethod}();
    }
}
