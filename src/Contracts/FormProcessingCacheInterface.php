<?php

namespace WebTheory\Saveyour\Contracts;

interface FormProcessingCacheInterface
{
    /**
     * @return FieldOperationCacheInterface[]
     */
    public function inputResults(): array;

    /**
     * @return array
     */
    public function inputViolations(): array;

    /**
     * @return array
     */
    public function requestViolations(): array;

    /**
     * @return FormDataProcessingCacheInterface[]
     */
    public function processingResults(): array;
}
