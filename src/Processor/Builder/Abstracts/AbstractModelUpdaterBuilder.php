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

    /**
     * @return $this
     */
    public function withName(string $name): AbstractModelUpdaterBuilder
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return $this
     */
    public function withFields(?array $fields): AbstractModelUpdaterBuilder
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * @return $this
     */
    public function withRepository(object $repository): AbstractModelUpdaterBuilder
    {
        $this->repository = $repository;

        return $this;
    }

    /**
     * @return $this
     */
    public function withUpdateMethod(?string $updateMethod): AbstractModelUpdaterBuilder
    {
        $this->updateMethod = $updateMethod;

        return $this;
    }

    /**
     * @return $this
     */
    public function withCommitMethod(?string $commitMethod): AbstractModelUpdaterBuilder
    {
        $this->commitMethod = $commitMethod;

        return $this;
    }
}
