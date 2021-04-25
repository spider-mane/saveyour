<?php

namespace WebTheory\Saveyour\Contracts;

use Psr\Http\Message\ServerRequestInterface;

interface FormFieldControllerInterface extends InputPurifierInterface
{
    /**
     *
     */
    public function getRequestVar(): string;

    /**
     * @return string[]
     */
    public function mustAwait(): array;

    /**
     *
     */
    public function getFormField(): ?FormFieldInterface;

    /**
     * @return mixed
     */
    public function getPresetValue(ServerRequestInterface $request);

    /**
     * @param ServerRequestInterface $request
     *
     * @return FormFieldInterface
     */
    public function render(ServerRequestInterface $request): ?FormFieldInterface;

    /**
     * @param ServerRequestInterface $request
     *
     * @return FieldOperationCacheInterface
     */
    public function process(ServerRequestInterface $request): FieldOperationCacheInterface;

    public function validate(ServerRequestInterface $request): bool;

    public function validated(ServerRequestInterface $request): array;
}
