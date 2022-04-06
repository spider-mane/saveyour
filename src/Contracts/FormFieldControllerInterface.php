<?php

namespace WebTheory\Saveyour\Contracts;

use Psr\Http\Message\ServerRequestInterface;

interface FormFieldControllerInterface extends ValidatorInterface
{
    public function getRequestVar(): string;

    /**
     * @return array<int,string>
     */
    public function mustAwait(): array;

    public function getFormField(): ?FormFieldInterface;

    public function getPresetValue(ServerRequestInterface $request);

    public function getUpdatedValue(ServerRequestInterface $request);

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
}
