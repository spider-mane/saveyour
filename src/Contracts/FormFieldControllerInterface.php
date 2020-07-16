<?php

namespace WebTheory\Saveyour\Contracts;

use Psr\Http\Message\ServerRequestInterface;

interface FormFieldControllerInterface extends InputPurifierInterface
{
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

    /**
     * @return string[]
     */
    public function mustAwait(): array;

    /**
     *
     */
    public function getRequestVar(): string;

    /**
     *
     */
    public function getFormField(): ?FormFieldInterface;

    /**
     *
     */
    public function getPresetValue(ServerRequestInterface $request);
}
