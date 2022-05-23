<?php

namespace WebTheory\Saveyour\Report\Builder;

use WebTheory\Saveyour\Contracts\Report\Builder\ProcessedFieldReportBuilderInterface;
use WebTheory\Saveyour\Contracts\Report\ProcessedFieldReportInterface;
use WebTheory\Saveyour\Report\ProcessedFieldReport;

class ProcessedFieldReportBuilder implements ProcessedFieldReportBuilderInterface
{
    protected $sanitizedInputValue = null;

    protected bool $updateAttempted = false;

    protected bool $updateSuccessful = false;

    public function __construct(?ProcessedFieldReportInterface $previous = null)
    {
        if ($previous) {
            $this->sanitizedInputValue = $previous->sanitizedInputValue();
            $this->updateAttempted = $previous->updateAttempted();
            $this->updateSuccessful = $previous->updateSuccessful();
        }
    }

    /**
     * @return $this
     */
    public function withSanitizedInputValue($value): ProcessedFieldReportBuilder
    {
        $this->sanitizedInputValue = $value;

        return $this;
    }

    /**
     * @return $this
     */
    public function withUpdateAttempted(bool $result): ProcessedFieldReportBuilder
    {
        $this->updateAttempted = $result;

        return $this;
    }

    /**
     * @return $this
     */
    public function withUpdateSuccessful(bool $result): ProcessedFieldReportBuilder
    {
        $this->updateSuccessful = $result;

        return $this;
    }

    public function build(): ProcessedFieldReportInterface
    {
        return new ProcessedFieldReport(
            $this->sanitizedInputValue,
            $this->updateAttempted,
            $this->updateSuccessful
        );
    }
}
