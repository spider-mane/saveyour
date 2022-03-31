<?php

namespace WebTheory\Saveyour\Controllers;

use ArrayAccess;
use JsonSerializable;
use WebTheory\Saveyour\Concerns\ImmutableObjectTrait;
use WebTheory\Saveyour\Contracts\FormDataProcessingCacheInterface;
use WebTheory\Saveyour\Contracts\FormProcessingCacheInterface;

class FormProcessingCache implements FormProcessingCacheInterface, ArrayAccess, JsonSerializable
{
    use ImmutableObjectTrait;

    protected $results = [
        'request_violations' => [],
        'input_violations' => [],
        'input_results' => [],
        'processing_results' => [],
    ];

    /**
     * {@inheritDoc}
     */
    public function requestViolations(): array
    {
        return $this->results['request_violations'];
    }

    public function withRequestViolations(array $violations)
    {
        $this->results['request_violations'] = $violations;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function inputViolations(): array
    {
        return $this->results['input_violations'];
    }

    public function withInputViolations(array $violations)
    {
        $this->results['input_violations'] = $violations;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function inputResults(): array
    {
        return $this->results['input_results'];
    }

    public function withInputResults(array $results)
    {
        $this->results['input_results'] = $results;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function processingResults(): array
    {
        return $this->results['processing_results'];
    }

    /**
     * @param FormDataProcessingCacheInterface[] $results
     */
    public function withProcessingResults(array $results)
    {
        $this->results['processing_results'] = $results;

        return $this;
    }

    public function toArray()
    {
        return $this->results;
    }

    public function offsetExists($offset)
    {
        return isset($this->results[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->results[$offset];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
