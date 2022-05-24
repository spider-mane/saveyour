<?php

namespace WebTheory\Saveyour\Controller\Abstracts;

use WebTheory\Saveyour\Contracts\Controller\FormFieldControllerInterface;
use WebTheory\Saveyour\Contracts\Data\FieldDataManagerInterface;
use WebTheory\Saveyour\Contracts\Field\FormFieldInterface;
use WebTheory\Saveyour\Contracts\Formatting\DataFormatterInterface;
use WebTheory\Saveyour\Controller\FormFieldController;
use WebTheory\Saveyour\Validation\Validator;

abstract class AbstractBuilderField
{
    protected string $requestVar;

    protected bool $isPermittedToProcess = false;

    protected array $mustAwait = [];

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
        return $this->isPermittedToProcess;
    }

    /**
     * Set the value of processingDisabled
     *
     * @param bool $processingDisabled
     *
     * @return $this
     */
    public function setProcessingDisabled(bool $processingDisabled): AbstractBuilderField
    {
        $this->isPermittedToProcess = $processingDisabled;

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
     * @return $this
     */
    public function setMustAwait(array $mustAwait): AbstractBuilderField
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
            $this->defineValidator(),
            $this->defineDataFormatter(),
            $this->definePermissionProcessStatus(),
            $this->defineMustAwait(),
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

    protected function defineValidator(): ?Validator
    {
        return null;
    }

    protected function definePermissionProcessStatus(): ?bool
    {
        return $this->isPermittedToProcess;
    }

    protected function defineMustAwait(): ?array
    {
        return $this->mustAwait;
    }
}
