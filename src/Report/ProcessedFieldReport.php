<?php

namespace WebTheory\Saveyour\Report;

use WebTheory\Saveyour\Contracts\ProcessedFieldReportInterface;

class ProcessedFieldReport implements ProcessedFieldReportInterface
{
    protected $sanitizedInputValue;

    protected bool $updateAttempted = false;

    protected bool $updateSuccessful = false;

    public function __construct(
        $sanitizedInputValue = null,
        bool $updateAttempted = false,
        bool $updateSuccessful = false
    ) {
        $this->sanitizedInputValue = $sanitizedInputValue;
        $this->updateAttempted = $updateAttempted;
        $this->updateSuccessful = $updateSuccessful;
    }

    public function sanitizedInputValue()
    {
        return $this->sanitizedInputValue;
    }

    public function updateAttempted(): bool
    {
        return $this->updateAttempted;
    }

    public function updateSuccessful(): bool
    {
        return $this->updateSuccessful;
    }

    public function toArray()
    {
        return [
            'sanitized_input_value' => $this->sanitizedInputValue,
            'update_attempted' => $this->updateAttempted,
            'update_successful' => $this->updateSuccessful,
        ];
    }

    public static function voided(): ProcessedFieldReportInterface
    {
        return new static(null, false, false);
    }
}
