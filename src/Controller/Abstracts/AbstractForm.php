<?php

namespace WebTheory\Saveyour\Controller\Abstracts;

use ArrayIterator;
use IteratorAggregate;
use Psr\Http\Message\ServerRequestInterface;
use Traversable;
use WebTheory\Saveyour\Contracts\Auth\FormShieldInterface;
use WebTheory\Saveyour\Contracts\Controller\FormFieldControllerInterface;
use WebTheory\Saveyour\Contracts\Controller\FormInterface;
use WebTheory\Saveyour\Contracts\Controller\FormSubmissionManagerInterface;
use WebTheory\Saveyour\Contracts\Processor\FormDataProcessorInterface;
use WebTheory\Saveyour\Contracts\Report\ProcessedFormReportInterface;
use WebTheory\Saveyour\Controller\FormSubmissionManager;

abstract class AbstractForm implements FormInterface, IteratorAggregate
{
    public function data(ServerRequestInterface $request): array
    {
        return [
            'method' => $this->method(),
            'action' => $this->action(),
            'checks' => $this->checks(),
            'fields' => $this->fields(),
        ];
    }

    public function process(ServerRequestInterface $request): ProcessedFormReportInterface
    {
        return $this->manager()->process($request);
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->fields());
    }

    public function html(ServerRequestInterface $request): ?string
    {
        return null;
    }

    protected function manager(): FormSubmissionManagerInterface
    {
        return new FormSubmissionManager(
            $this->controllers(),
            $this->processors(),
            $this->shield()
        );
    }

    protected function shield(): ?FormShieldInterface
    {
        return null;
    }

    /**
     * @return array<string, FormFieldControllerInterface>
     */
    protected function controllers(): array
    {
        return [];
    }

    /**
     * @return array<string,FormDataProcessorInterface>
     */
    protected function processors(): array
    {
        return [];
    }

    abstract protected function action(): string;

    abstract protected function method(): string;

    abstract protected function checks(): string;

    abstract protected function fields(): iterable;
}
