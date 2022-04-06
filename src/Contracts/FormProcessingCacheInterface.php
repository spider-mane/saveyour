<?php

namespace WebTheory\Saveyour\Contracts;

interface FormProcessingCacheInterface
{
    /**
     * @return array<int,FieldOperationCacheInterface>
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
     * @return array<int,FormDataProcessingCacheInterface>
     */
    public function processingResults(): array;
}
