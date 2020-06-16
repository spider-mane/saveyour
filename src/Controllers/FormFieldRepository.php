<?php

namespace WebTheory\Saveyour\Controllers;

use WebTheory\Saveyour\Contracts\FormFieldControllerInterface;
use WebTheory\Saveyour\Contracts\FormFieldRepositoryInterface;

class FormFieldRepository implements FormFieldRepositoryInterface
{
    /**
     * @var FormFieldControllerInterface[]
     */
    protected $fields = [];

    /**
     *
     */
    public function __construct(FormFieldControllerInterface ...$fields)
    {
        array_map([$this, 'addField'], $fields);
    }

    /**
     * Get the value of fields
     *
     * @return mixed
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     *
     */
    public function getField(string $field): ?FormFieldControllerInterface
    {
        return $this->fields[$field] ?? null;
    }

    /**
     *
     */
    public function addField(FormFieldControllerInterface $field)
    {
        $this->fields[$field->getRequestVar()] = $field;
    }
}
