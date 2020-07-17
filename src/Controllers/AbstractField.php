<?php

namespace WebTheory\Saveyour\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Validatable;
use WebTheory\Saveyour\Contracts\DataTransformerInterface;
use WebTheory\Saveyour\Contracts\FieldDataManagerInterface;
use WebTheory\Saveyour\Contracts\FieldOperationCacheInterface;
use WebTheory\Saveyour\Contracts\FormFieldControllerInterface;
use WebTheory\Saveyour\Contracts\FormFieldInterface;

abstract class AbstractField implements FormFieldControllerInterface
{
    /**
     * @var string
     */
    protected $__requestVar;

    /**
     * @var bool
     */
    protected $__processingDisabled = false;

    /**
     * @var array
     */
    protected $__mustAwait = [];

    /**
     * @var FormFieldControllerInterface
     */
    protected $__coreController;

    /**
     *
     */
    public function __construct(string $requestVar, array $mustAwait = [], $processingDisabled = false)
    {
        $this->__requestVar = $requestVar;
        $this->__mustAwait = $mustAwait;
        $this->__processingDisabled = $processingDisabled;

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

    /**
     *
     */
    public function getFormField(): ?FormFieldInterface
    {
        return $this->__coreController->getFormField();
    }

    /**
     *
     */
    public function getPresetValue(ServerRequestInterface $request)
    {
        return $this->__coreController->getPresetValue($request);
    }

    /**
     *
     */
    public function mustAwait(): array
    {
        return $this->__coreController->mustAwait();
    }

    /**
     *
     */
    public function render(ServerRequestInterface $request): ?FormFieldInterface
    {
        return $this->__coreController->render($request);
    }

    /**
     *
     */
    public function process(ServerRequestInterface $request): FieldOperationCacheInterface
    {
        return $this->__coreController->process($request);
    }

    /**
     * @return Validatable[]
     */
    public function getRules(): array
    {
        return $this->__coreController->getRules();
    }

    /**
     * @return Validatable
     */
    public function getRule(string $rule): Validatable
    {
        return $this->__coreController->getRule($rule);
    }

    /**
     * @return callable[]
     */
    public function getFilters(): array
    {
        return $this->__coreController->getFilters();
    }

    /**
     * @return bool|mixed
     */
    public function filterInput($input)
    {
        return $this->__coreController->filterInput($input);
    }

    /**
     * @return array
     */
    public function getViolations(): array
    {
        return $this->__coreController->getViolations();
    }

    /**
     *
     */
    protected function createFormFieldController(): FormFieldControllerInterface
    {
        return new BaseFormFieldController(
            $this->defineRequestVar(),
            $this->defineFormField(),
            $this->defineDataManager(),
            $this->defineDataTransformer(),
            $this->defineFilters(),
            $this->defineRules(),
            $this->defineEscaper(),
            $this->defineProcessingDisabled(),
            $this->defineMustAwait()
        );
    }

    /**
     *
     */
    protected function defineRequestVar(): string
    {
        return $this->__requestVar;
    }

    /**
     *
     */
    protected function defineFormField(): ?FormFieldInterface
    {
        return null;
    }

    /**
     *
     */
    protected function defineDataManager(): ?FieldDataManagerInterface
    {
        return null;
    }

    /**
     *
     */
    protected function defineDataTransformer(): ?DataTransformerInterface
    {
        return null;
    }

    /**
     *
     */
    protected function defineFilters(): ?array
    {
        return null;
    }

    /**
     *
     */
    protected function defineRules(): ?array
    {
        return null;
    }

    /**
     *
     */
    protected function defineEscaper(): ?callable
    {
        return null;
    }

    /**
     *
     */
    protected function defineProcessingDisabled(): ?bool
    {
        return $this->__processingDisabled;
    }

    /**
     *
     */
    protected function defineMustAwait(): ?array
    {
        return $this->__mustAwait;
    }
}
