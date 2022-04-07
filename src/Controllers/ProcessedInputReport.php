<?php

namespace WebTheory\Saveyour\Controllers;

use WebTheory\Saveyour\Contracts\ProcessedInputReportInterface;

class ProcessedInputReport extends ProcessedFieldReport implements ProcessedInputReportInterface
{
    protected bool $requestVarPresent;

    protected ?string $rawInputValue = null;

    public function __construct(
        bool $requestVarPresent,
        ?string $inputValue,
        $sanitizedInputValue,
        bool $updateAttempted,
        bool $updateSuccessful,
        bool $validationStatus,
        array $ruleViolations
    ) {
        parent::__construct(
            $sanitizedInputValue,
            $updateAttempted,
            $updateSuccessful,
            $validationStatus,
            $ruleViolations
        );

        $this->requestVarPresent = $requestVarPresent;
        $this->rawInputValue = $inputValue;
    }

    public function requestVarPresent(): bool
    {
        return $this->requestVarPresent;
    }

    public function rawInputValue(): ?string
    {
        return $this->rawInputValue;
    }

    public static function voided(): ProcessedInputReportInterface
    {
        return new static(false, null, null, false, false, false, []);
    }
}
