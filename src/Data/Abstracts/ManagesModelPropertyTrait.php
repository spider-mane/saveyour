<?php

namespace WebTheory\Saveyour\Data\Abstracts;

use Jawira\CaseConverter\Convert;

trait ManagesModelPropertyTrait
{
    protected string $property;

    protected function getModelData(object $model): ?object
    {
        return $model->{$this->resolveMethod('get')}();
    }

    protected function setModelData(object $model, $data): void
    {
        $model->{$this->resolveMethod('set')}($data);
    }

    protected function resolveMethod(string $action): string
    {
        return $action . (new Convert($this->property))->toPascal();
    }
}
