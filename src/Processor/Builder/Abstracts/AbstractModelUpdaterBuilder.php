<?php

namespace WebTheory\Saveyour\Processor\Builder\Abstracts;

class AbstractModelUpdaterBuilder
{
    protected string $name;

    protected ?array $fields = [];

    /**
     * Instance of model repository
     */
    protected object $repository;

    /**
     * Optional method for updating the model in the repository
     */
    protected ?string $updateMethod = null;

    /**
     * Optional method for committing repository changes to database
     */
    protected ?string $commitMethod = null;

    public function withName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function withFields(?array $fields): self
    {
        $this->fields = $fields;

        return $this;
    }

    public function withRepository(object $repository): self
    {
        $this->repository = $repository;

        return $this;
    }

    public function withUpdateMethod(?string $updateMethod): self
    {
        $this->updateMethod = $updateMethod;

        return $this;
    }

    public function withCommitMethod(?string $commitMethod): self
    {
        $this->commitMethod = $commitMethod;

        return $this;
    }
}
