<?php

namespace WebTheory\Saveyour\Fields;

use WebTheory\Saveyour\Contracts\FormFieldInterface;

class Choices extends Select implements FormFieldInterface
{
    protected ?array $config = null;

    protected function getConfiguration(): string
    {
        return json_encode($this->resolveConfiguration() + $this->getConfig(), JSON_THROW_ON_ERROR);
    }

    protected function resolveConfiguration(): array
    {
        return [];
    }

    protected function getConfig()
    {
        return $this->config;
    }
}
