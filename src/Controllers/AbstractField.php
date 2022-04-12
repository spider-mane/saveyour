<?php

namespace WebTheory\Saveyour\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use WebTheory\Saveyour\Contracts\DataFormatterInterface;
use WebTheory\Saveyour\Contracts\FieldDataManagerInterface;
use WebTheory\Saveyour\Contracts\FormFieldControllerInterface;
use WebTheory\Saveyour\Contracts\FormFieldInterface;
use WebTheory\Saveyour\Contracts\ProcessedFieldReportInterface;
use WebTheory\Saveyour\Contracts\ValidationReportInterface;
use WebTheory\Saveyour\Validation\Validator;

abstract class AbstractField implements FormFieldControllerInterface
{
    /**
     * @var string
     */
    protected string $__requestVar;

    /**
     * @var bool
     */
    protected bool $__isPermittedToProcess = true;

    /**
     * @var array<int,string>
     */
    protected array $__mustAwait = [];

    protected FormFieldControllerInterface $__coreController;

    public function __construct(string $requestVar, array $mustAwait = [], $processingEnabled = true)
    {
        $this->__requestVar = $requestVar;
        $this->__mustAwait = $mustAwait;
        $this->__isPermittedToProcess = $processingEnabled;

        $this->__coreController = $this->createFormFieldController();
    }

    /**
     * Get the value of requestVar
     *
     * @return string
     */
    public function getRequestVar(): string
    {
        return $this->__coreController->getRequestVar();
    }

    public function getFormField(): ?FormFieldInterface
    {
        return $this->__coreController->getFormField();
    }

    public function requestVarPresent(ServerRequestInterface $request): bool
    {
        return $this->__coreController->requestVarPresent($request);
    }

    public function inspect($value): ValidationReportInterface
    {
        return $this->__coreController->inspect($value);
    }

    public function validate($value): bool
    {
        return $this->__coreController->validate($value);
    }

    public function getPresetValue(ServerRequestInterface $request)
    {
        return $this->__coreController->getPresetValue($request);
    }

    public function getUpdatedValue(ServerRequestInterface $request): ?array
    {
        return $this->__coreController->getUpdatedValue($request);
    }

    public function mustAwait(): array
    {
        return $this->__coreController->mustAwait();
    }

    public function render(ServerRequestInterface $request): string
    {
        return $this->__coreController->render($request);
    }

    public function compose(ServerRequestInterface $request): FormFieldInterface
    {
        return $this->__coreController->compose($request);
    }

    public function isPermittedToProcess(): bool
    {
        return $this->__coreController->isPermittedToProcess();
    }

    public function process(ServerRequestInterface $request): ProcessedFieldReportInterface
    {
        return $this->__coreController->process($request);
    }

    protected function createFormFieldController(): FormFieldControllerInterface
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
        return $this->__requestVar;
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
        return $this->__isPermittedToProcess;
    }

    protected function defineMustAwait(): ?array
    {
        return $this->__mustAwait;
    }
}
