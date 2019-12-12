<?php

namespace WebTheory\Saveyour\Contracts;

use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Validatable;

interface FormFieldControllerInterface
{
    /**
     * @param ServerRequestInterface $request
     *
     * @return FormFieldInterface
     */
    public function render(ServerRequestInterface $request): FormFieldInterface;

    /**
     * @param ServerRequestInterface $request
     *
     * @return FieldOperationCacheInterface
     */
    public function process(ServerRequestInterface $request): FieldOperationCacheInterface;

    /**
     *
     */
    public function getRequestVar(): string;

    /**
     *
     */
    public function canProcessInput(): bool;

    /**
     *
     */
    public function getFormField(): ?FormFieldInterface;

    /**
     *
     */
    public function setFormField(FormFieldInterface $formField);

    /**
     *
     */
    public function addAlert(string $rule, string $alert);

    /**
     *
     */
    public function addRule(string $rule, Validatable $validator, ?string $alert = null);

    /**
     *
     */
    public function getRules(): array;

    /**
     *
     */
    public function getRule(string $rule): Validatable;

    /**
     *
     */
    public function addFilter(callable $filter);

    /**
     *
     */
    public function setFilters(callable ...$filters);

    /**
     *
     */
    public function getFilters(): array;

    /**
     *
     */
    public function setEscaper(?callable $escaper);

    /**
     *
     */
    public function getEscaper(): ?callable;

    /**
     *
     */
    public function getTransformer(): DataTransformerInterface;
}
