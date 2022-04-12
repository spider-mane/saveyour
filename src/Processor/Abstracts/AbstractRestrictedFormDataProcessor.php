<?php

namespace WebTheory\Saveyour\Processor\Abstracts;

use InvalidArgumentException;
use WebTheory\Saveyour\Contracts\Processor\FormDataProcessorInterface;

abstract class AbstractRestrictedFormDataProcessor extends AbstractFormDataProcessor implements FormDataProcessorInterface
{
    public const ACCEPTED_FIELDS = [];

    protected function addField(string $field, string $param): void
    {
        if (!in_array($field, static::ACCEPTED_FIELDS, true)) {
            throw new InvalidArgumentException("{$field} is not an accepted value");
        }

        parent::addField($field, $param);
    }
}
