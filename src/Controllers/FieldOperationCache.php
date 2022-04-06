<?php

namespace WebTheory\Saveyour\Controllers;

use JsonSerializable;
use WebTheory\Saveyour\Contracts\FieldOperationCacheInterface;

class FieldOperationCache extends AbstractFieldOperationCache implements FieldOperationCacheInterface, JsonSerializable
{
    public function __construct(
        bool $requestVarPresent = false,
        $sanitizedInputValue = null,
        bool $updateAttempted = false,
        bool $updateSuccessful = false,
        bool $validationStatus = false,
        array $ruleViolations = []
    ) {
        $this->requestVarPresent = $requestVarPresent;
        $this->sanitizedInputValue = $sanitizedInputValue;
        $this->updateAttempted = $updateAttempted;
        $this->updateSuccessful = $updateSuccessful;
        $this->validationStatus = $validationStatus;
        $this->ruleViolations = $ruleViolations;
    }
}
