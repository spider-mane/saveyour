<?php

namespace WebTheory\Saveyour\Processor\Abstracts;

use WebTheory\Saveyour\Contracts\Processor\FormDataProcessorInterface;
use WebTheory\Saveyour\Contracts\Report\ProcessedFieldReportInterface;
use WebTheory\Saveyour\Contracts\Report\ProcessedInputReportInterface;

abstract class AbstractFormDataProcessor implements FormDataProcessorInterface
{
    protected string $name;

    protected ?array $fields = null;

    public function __construct(string $name, ?array $fields)
    {
        $this->name = $name;
        $this->setFields($fields);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getFields(): ?array
    {
        return $this->fields;
    }

    protected function setFields(?array $fields): void
    {
        if (empty($fields)) {
            $this->fields = $fields;
        } else {
            foreach ($fields as $field => $param) {
                $this->addField($field, $param);
            }
        }
    }

    protected function addField(string $id, string $field): void
    {
        $this->fields[$id] = $field;
    }

    /**
     * @param array<string,ProcessedFieldReportInterface> $results
     */
    protected function hasReasonToProcess(array $results): bool
    {
        return $this->hasNoFields() || $this->valueUpdated($results);
    }

    protected function hasNoFields(): bool
    {
        return is_array($fields = $this->getFields()) && empty($fields);
    }

    /**
     * @param array<string,ProcessedFieldReportInterface> $results
     */
    protected function valueUpdated(array $results): bool
    {
        foreach ($results as $result) {
            if ($result->updateSuccessful()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array<string,ProcessedInputReportInterface> $results
     */
    protected function allFieldsPresent(array $results): bool
    {
        foreach ($this->fields ?? array_keys($results) as $field) {
            $field = $results[$field] ?? null;

            if (!$field || !$this->fieldIsPresentAndValid($field)) {
                return false;
            }
        }

        return true;
    }

    protected function fieldIsPresentAndValid(ProcessedInputReportInterface $report): bool
    {
        return $report->requestVarPresent() && $report->validationStatus();
    }

    /**
     * @param array<string,ProcessedInputReportInterface> $results
     */
    protected function extractValues(array $results): array
    {
        $values = [];
        $keys = array_flip($this->fields);

        foreach ($results as $id => $report) {
            $values[$keys[$id]] = $report->rawInputValue();
        }

        return $values;
    }

    /**
     * @param array<string,ProcessedInputReportInterface> $results
     */
    protected function mappedValues(array $results)
    {
        $values = [];

        foreach ($results as $field => $report) {
            $values[$field] = $report->rawInputValue();
        }

        return $values;
    }
}
