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
     * @var FormFieldControllerInterface
     */
    protected $__coreController;

    /**
     *
     */
    public function __construct(string $requestVar)
    {
        $this->__coreController = $this->createFormFieldController($requestVar);
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
    public function getPresetValue(ServerRequestInterface $request)
    {
        return $this->__coreController->getPresetValue($request);
    }

    /**
     *
     */
    public function canProcessInput(): bool
    {
        return $this->__coreController->canProcessInput();
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
     *
     */
    public function mustAwait(): array
    {
        return $this->__coreController->mustAwait();
    }

    /**
     *
     */
    protected function createFormFieldController(string $requestVar): FormFieldControllerInterface
    {
        return new BaseFormFieldController(
            $requestVar,
            $this->createFormField(),
            $this->createDataManager(),
            $this->createDataTransformer(),
            $this->defineFilters(),
            $this->defineRules()
        );
    }

    /**
     *
     */
    protected function createFormField(): ?FormFieldInterface
    {
        return null;
    }

    /**
     *
     */
    protected function createDataManager(): ?FieldDataManagerInterface
    {
        return null;
    }

    /**
     *
     */
    protected function createDataTransformer(): ?DataTransformerInterface
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
}
