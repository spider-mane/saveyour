<?php

namespace WebTheory\Saveyour\Processor;

use Psr\Http\Message\ServerRequestInterface;
use WebTheory\Saveyour\Abstracts\QueriesRepositoryTrait;
use WebTheory\Saveyour\Contracts\Processor\FormDataProcessorInterface;
use WebTheory\Saveyour\Contracts\Report\FormProcessReportInterface;
use WebTheory\Saveyour\Enum\ServerRequestLocation;
use WebTheory\Saveyour\Processor\Abstracts\AbstractFormDataProcessor;

class QueriedModelUpdater extends AbstractFormDataProcessor implements FormDataProcessorInterface
{
    use QueriesRepositoryTrait;

    public function __construct(
        string $name,
        ?array $fields,
        object $repository,
        string $queryMethod,
        string $updateMethod,
        string $lookup,
        ?ServerRequestLocation $location = null,
        ?string $commitMethod = null
    ) {
        parent::__construct($name, $fields);

        $this->repository = $repository;
        $this->queryMethod = $queryMethod;
        $this->updateMethod = $updateMethod;
        $this->lookup = $lookup;
        $this->commitMethod = $commitMethod;
        $this->location = $location ?? ServerRequestLocation::Attribute();
    }

    public function process(ServerRequestInterface $request, array $results): ?FormProcessReportInterface
    {
        if ($this->hasReasonToProcess($results)) {
            $this->updateModel($request);
        }

        return null;
    }

    protected function updateModel(ServerRequestInterface $request): void
    {
        $this->updateRepository($this->getModelFromRepository($request));
        $this->maybeCommitRepositoryChanges();
    }
}
