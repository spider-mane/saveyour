<?php

namespace WebTheory\Saveyour\Fields;

use WebTheory\Saveyour\Contracts\FormFieldInterface;

class Choices extends Select implements FormFieldInterface
{
    /**
     * @var array
     */
    protected $config;

    protected function getConfiguration(): string
    {
        return json_encode($this->resolveConfiguration() + $this->getConfig());
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
