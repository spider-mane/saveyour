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
    protected $controller;

    /**
     *
     */
    public function __construct(string $requestVar)
    {
        $this->controller = $this->createFormFieldController($requestVar);
    }

    /**
     * Get the value of requestVar
     *
     * @return string
     */
    public function getRequestVar(): string
    {
        return $this->controller->getRequestVar();
    }

    /**
     * @return Validatable[]
     */
    public function getRules(): array
    {
        return $this->controller->getRules();
    }

    /**
     * @return Validatable
     */
    public function getRule(string $rule): Validatable
    {
        return $this->controller->getRule($rule);
    }

    /**
     * @return callable[]
     */
    public function getFilters(): array
    {
        return $this->controller->getFilters();
    }


    /**
     * @return bool|mixed
     */
    public function filterInput($input)
    {
        return $this->controller->filterInput($input);
    }

    /**
     * @return array
     */
    public function getViolations(): array
    {
        return $this->controller->getViolations();
    }
    /**
     *
     */
    public function getPresetValue(ServerRequestInterface $request)
    {
        return $this->controller->getPresetValue($request);
    }

    /**
     *
     */
    public function canProcessInput(): bool
    {
        return $this->controller->canProcessInput();
    }

    /**
     *
     */
    public function render(ServerRequestInterface $request): ?FormFieldInterface
    {
        return $this->controller->render($request);
    }

    /**
     *
     */
    public function process(ServerRequestInterface $request): FieldOperationCacheInterface
    {
        return $this->controller->process($request);
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
    public function createDataManager(): ?FieldDataManagerInterface
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
