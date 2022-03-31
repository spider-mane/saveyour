<?php

namespace WebTheory\Saveyour\Controllers;

use WebTheory\Saveyour\Contracts\DataFormatterInterface;
use WebTheory\Saveyour\Contracts\FieldDataManagerInterface;
use WebTheory\Saveyour\Contracts\FormFieldControllerInterface;
use WebTheory\Saveyour\Contracts\FormFieldInterface;

abstract class AbstractBuilderField
{
    /**
     * @var string
     */
    protected $requestVar;

    /**
     * @var bool
     */
    protected $processingDisabled = false;

    /**
     * @var array
     */
    protected $mustAwait = [];

    public function __construct(string $requestVar)
    {
        $this->requestVar = $requestVar;
    }

    /**
     * Get the value of processingDisabled
     *
     * @return bool
     */
    public function getProcessingDisabled(): bool
    {
        return $this->processingDisabled;
    }

    /**
     * Set the value of processingDisabled
     *
     * @param bool $processingDisabled
     *
     * @return self
     */
    public function setProcessingDisabled(bool $processingDisabled)
    {
        $this->processingDisabled = $processingDisabled;

        return $this;
    }

    /**
     * Get the value of mustAwait
     *
     * @return array
     */
    public function getMustAwait(): array
    {
        return $this->mustAwait;
    }

    /**
     * Set the value of mustAwait
     *
     * @param array $mustAwait
     *
     * @return self
     */
    public function setMustAwait(array $mustAwait)
    {
        $this->mustAwait = $mustAwait;

        return $this;
    }

    public function init(): FormFieldControllerInterface
    {
        return new FormFieldController(
            $this->defineRequestVar(),
            $this->defineFormField(),
            $this->defineDataManager(),
            $this->defineDataFormatter(),
            $this->defineFilters(),
            $this->defineRules(),
            $this->defineEscaper(),
            $this->defineProcessingDisabled(),
            $this->defineMustAwait()
        );
    }

    protected function defineRequestVar(): string
    {
        return $this->requestVar;
    }

    protected function defineFormField(): ?FormFieldInterface
    {
        return null;
    }

    protected function defineDataManager(): ?FieldDataManagerInterface
    {
        return null;
    }

    protected function defineDataFormatter(): ?DataFormatterInterface
    {
        return null;
    }

    protected function defineFilters(): ?array
    {
        return null;
    }

    protected function defineRules(): ?array
    {
        return null;
    }

    protected function defineEscaper(): ?callable
    {
        return null;
    }

    protected function defineProcessingDisabled(): ?bool
    {
        return $this->processingDisabled;
    }

    protected function defineMustAwait(): ?array
    {
        return $this->mustAwait;
    }
}
