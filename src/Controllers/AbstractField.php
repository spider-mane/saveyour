<?php

namespace WebTheory\Saveyour\Controllers;

use Psr\Http\Message\ServerRequestInterface;
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
     * @var string
     */
    protected $requestVar;

    /**
     *
     */
    public function __construct(string $requestVar)
    {
        $this->requestVar = $requestVar;
        $this->controller = $this->createFormFieldController();
    }

    /**
     * Get the value of requestVar
     *
     * @return string
     */
    public function getRequestVar(): string
    {
        return $this->requestVar;
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
    protected function createFormFieldController(): FormFieldControllerInterface
    {
        return new BaseFormFieldController(
            $this->getRequestVar(),
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
