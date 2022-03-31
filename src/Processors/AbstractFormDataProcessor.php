<?php

namespace WebTheory\Saveyour\Processors;

use WebTheory\Saveyour\Contracts\FieldOperationCacheInterface;
use WebTheory\Saveyour\Contracts\FormDataProcessorInterface;

abstract class AbstractFormDataProcessor implements FormDataProcessorInterface
{
    /**
     * @var array
     */
    protected $fields = [];

    /**
     * Get the value of fields
     *
     * @return mixed
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    public function getField(string $field)
    {
        return $this->fields[$field];
    }

    /**
     * Set the value of fields
     *
     * @param mixed $fields
     *
     * @return self
     */
    public function setFields(array $fields)
    {
        foreach ($fields as $field => $param) {
            $this->addField($field, $param);
        }
    }

    /**
     * @param string $id
     * @param string $field
     */
    public function addField(string $id, string $field)
    {
        $this->fields[$id] = $field;

        return $this;
    }

    /**
     * @param FieldOperationCacheInterface[] $results
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
     * @param FieldOperationCacheInterface[] $results
     */
    protected function allFieldsPresent(array $results): bool
    {
        foreach ($this->fields as $field) {
            if (!isset($results[$field])) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param FieldOperationCacheInterface[] $results
     */
    protected function extractValues(array $results): array
    {
        $values = [];
        $keys = array_flip($this->fields);

        foreach ($results as $id => $cache) {
            $values[$keys[$id]] = $cache->sanitizedInputValue();
        }

        return $values;
    }
}
