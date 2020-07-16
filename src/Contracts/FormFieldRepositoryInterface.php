<?php

namespace WebTheory\Saveyour\Contracts;

interface FormFieldRepositoryInterface
{
    /**
     *
     */
    public function getField(string $field): ?FormFieldControllerInterface;

    /**
     * @return FormFieldInterface[]
     */
    public function getFields(string ...$fields): array;

    /**
     * @return string[]
     */
    public function getFieldNames(): array;
}
