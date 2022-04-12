<?php

namespace WebTheory\Saveyour\Processor;

use Closure;
use Psr\Http\Message\ServerRequestInterface;
use WebTheory\Saveyour\Contracts\FormDataProcessorInterface;
use WebTheory\Saveyour\Contracts\FormProcessReportInterface;
use WebTheory\Saveyour\Processor\Abstracts\AbstractFormDataProcessor;

class FormSubmissionClosure extends AbstractFormDataProcessor implements FormDataProcessorInterface
{
    protected Closure $closure;

    public function __construct(string $name, ?array $fields, Closure $closure)
    {
        parent::__construct($name, $fields);

        $this->closure = $closure;
    }

    /**
     * {@inheritDoc}
     */
    public function process(ServerRequestInterface $request, array $results): ?FormProcessReportInterface
    {
        return $this->closure->call($this, $request, $results);
    }
}
