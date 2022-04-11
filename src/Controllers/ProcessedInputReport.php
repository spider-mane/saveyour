<?php

namespace WebTheory\Saveyour\Controllers;

use WebTheory\Saveyour\Contracts\ProcessedFieldReportInterface;
use WebTheory\Saveyour\Contracts\ProcessedInputReportInterface;

class ProcessedInputReport extends ProcessedFieldReport implements ProcessedInputReportInterface
{
    protected bool $requestVarPresent;

    protected ?string $rawInputValue = null;

    protected bool $validationStatus;

    protected array $ruleViolations;

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
            $updateSuccessful
        );

        $this->requestVarPresent = $requestVarPresent;
        $this->rawInputValue = $inputValue;
        $this->validationStatus = $validationStatus;
        $this->ruleViolations = $ruleViolations;
    }

    public function requestVarPresent(): bool
    {
        return $this->requestVarPresent;
    }

    public function rawInputValue(): ?string
    {
        return $this->rawInputValue;
    }

    public function validationStatus(): bool
    {
        return $this->validationStatus;
    }

    public function ruleViolations(): array
    {
        return $this->ruleViolations;
    }

    public static function voided(): ProcessedInputReportInterface
    {
        return new static(false, null, null, false, false, false, []);
    }

    public static function invalid(string $input, array $violations): ProcessedInputReportInterface
    {
        return new static(true, $input, null, false, false, false, $violations);
    }

    public static function unprocessed(string $input): ProcessedInputReportInterface
    {
        return new static(true, $input, null, false, false, true, []);
    }

    public static function processed(string $input, ProcessedFieldReportInterface $field): ProcessedInputReportInterface
    {
        return new static(
            true,
            $input,
            $field->sanitizedInputValue(),
            $field->updateAttempted(),
            $field->updateSuccessful(),
            true,
            []
        );
    }
}
