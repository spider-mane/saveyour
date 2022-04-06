<?php

namespace WebTheory\Saveyour\Processors;

use Psr\Http\Message\ServerRequestInterface;
use WebTheory\Saveyour\Contracts\FormDataProcessorInterface;
use WebTheory\Saveyour\Contracts\FormProcessReportInterface;

class FormSubmissionCallback extends AbstractFormDataProcessor implements FormDataProcessorInterface
{
    /**
     * @var callable[]
     */
    protected $callbacks = [];

    public function __construct(callable ...$callbacks)
    {
        $this->callbacks = $callbacks;
    }

    /**
     * Get the value of callbacks
     *
     * @return callable[]
     */
    public function getCallbacks(): array
    {
        return $this->callbacks;
    }

    /**
     * add a callback function
     *
     * @param callable $callback
     *
     * @return self
     */
    public function addCallBack(callable $callback)
    {
        $this->callbacks[] = $callback;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function process(ServerRequestInterface $request, array $results): ?FormProcessReportInterface
    {
        foreach ($this->callbacks as $cb) {
            $cb($request, $results);
        }

        return null;
    }
}
