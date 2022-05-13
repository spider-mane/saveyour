<?php

namespace WebTheory\Saveyour\Processor;

use Psr\Http\Message\ServerRequestInterface;
use WebTheory\Saveyour\Abstracts\QueriesRepositoryTrait;
use WebTheory\Saveyour\Contracts\Processor\FormDataProcessorInterface;
use WebTheory\Saveyour\Contracts\Report\FormProcessReportInterface;
use WebTheory\Saveyour\Processor\Abstracts\AbstractFormDataProcessor;

class RepositoryCommitment extends AbstractFormDataProcessor implements FormDataProcessorInterface
{
    use QueriesRepositoryTrait;

    public function __construct(string $name, ?array $fields, object $repository, string $commitMethod)
    {
        parent::__construct($name, $fields);

        $this->repository = $repository;
        $this->commitMethod = $commitMethod;
    }

    public function process(ServerRequestInterface $request, array $results): ?FormProcessReportInterface
    {
        if ($this->hasReasonToProcess($results)) {
            $this->commitRepositoryChanges();
        }

        return null;
    }
}
