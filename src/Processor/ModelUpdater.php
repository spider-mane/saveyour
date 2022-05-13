<?php

namespace WebTheory\Saveyour\Processor;

use Psr\Http\Message\ServerRequestInterface;
use WebTheory\Saveyour\Abstracts\QueriesRepositoryTrait;
use WebTheory\Saveyour\Contracts\Processor\FormDataProcessorInterface;
use WebTheory\Saveyour\Contracts\Report\FormProcessReportInterface;
use WebTheory\Saveyour\Processor\Abstracts\AbstractFormDataProcessor;

class ModelUpdater extends AbstractFormDataProcessor implements FormDataProcessorInterface
{
    use QueriesRepositoryTrait;

    protected object $model;

    public function __construct(
        string $name,
        ?array $fields,
        object $model,
        object $repository,
        string $updateMethod,
        ?string $commitMethod = null
    ) {
        parent::__construct($name, $fields);

        $this->model = $model;
        $this->repository = $repository;
        $this->updateMethod = $updateMethod;
        $this->commitMethod = $commitMethod;
    }

    public function process(ServerRequestInterface $request, array $results): ?FormProcessReportInterface
    {
        if ($this->hasReasonToProcess($results)) {
            $this->updateModel();
        }

        return null;
    }

    protected function updateModel(): void
    {
        $this->updateRepository($this->model);
        $this->maybeCommitRepositoryChanges();
    }
}
