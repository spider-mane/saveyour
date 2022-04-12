<?php

namespace WebTheory\Saveyour\Processor;

use Psr\Http\Message\ServerRequestInterface;
use WebTheory\Saveyour\Contracts\FormDataProcessorInterface;
use WebTheory\Saveyour\Contracts\FormProcessReportInterface;
use WebTheory\Saveyour\Processor\Abstracts\AbstractFormDataProcessor;

class FormSubmissionCallback extends AbstractFormDataProcessor implements FormDataProcessorInterface
{
    /**
     * @var callable
     */
    protected $callback;

    public function __construct(string $name, ?array $fields, callable $callback)
    {
        parent::__construct($name, $fields);

        $this->callback = $callback;
    }

    /**
     * {@inheritDoc}
     */
    public function process(ServerRequestInterface $request, array $results): ?FormProcessReportInterface
    {
        return ($this->callback)($request, $results, $this);
    }
}
