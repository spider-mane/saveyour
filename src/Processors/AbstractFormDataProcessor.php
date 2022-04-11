<?php

namespace WebTheory\Saveyour\Processors;

use WebTheory\Saveyour\Contracts\FormDataProcessorInterface;
use WebTheory\Saveyour\Contracts\ProcessedFieldReportInterface;
use WebTheory\Saveyour\Contracts\ProcessedInputReportInterface;

abstract class AbstractFormDataProcessor implements FormDataProcessorInterface
{
    protected string $name;

    protected array $fields = [];

    public function __construct(string $name, array $fields)
    {
        $this->name = $name;

        foreach ($fields as $field => $param) {
            $this->addField($field, $param);
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    protected function addField(string $id, string $field)
    {
        $this->fields[$id] = $field;

        return $this;
    }

    /**
     * @param ProcessedFieldReportInterface[] $results
     */
    protected function valueUpdated(array $results): bool
    {
        foreach ($results as $result) {
            if (true === $result->updateSuccessful()) {
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
        foreach ($this->fields as $field) {
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
}
