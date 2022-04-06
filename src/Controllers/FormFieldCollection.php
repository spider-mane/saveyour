<?php

namespace WebTheory\Saveyour\Controllers;

use WebTheory\Saveyour\Contracts\FormFieldCollectionInterface;
use WebTheory\Saveyour\Contracts\FormFieldControllerInterface;

class FormFieldCollection implements FormFieldCollectionInterface
{
    /**
     * @var array<string,FormFieldControllerInterface>
     */
    protected $fields = [];

    public function __construct(FormFieldControllerInterface ...$fields)
    {
        array_map([$this, 'addField'], $fields);
    }

    /**
     * Get the value of fields
     *
     * @return mixed
     */
    public function getFields(string ...$fields): array
    {
        return empty($fields) ? $this->fields : $this->getDefinedFields(...$fields);
    }

    public function getField(string $field): ?FormFieldControllerInterface
    {
        return $this->fields[$field] ?? null;
    }

    public function getDefinedFields(string ...$fields): array
    {
        return array_filter(
            $this->fields,
            function (FormFieldControllerInterface $field) use ($fields) {
                return in_array($field->getRequestVar(), $fields);
            }
        );
    }

    public function addField(FormFieldControllerInterface $field)
    {
        $this->fields[$field->getRequestVar()] = $field;
    }

    /**
     * @return string[]
     */
    public function getFieldNames(): array
    {
        return array_keys($this->fields);
    }
}
