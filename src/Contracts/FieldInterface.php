<?php

namespace WebTheory\Saveyour\Contracts;

use Psr\Http\Message\ServerRequestInterface;

interface FieldInterface
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
     *
     */
    public function getRequestVar(): string;

    /**
     *
     */
    public function getPresetValue(ServerRequestInterface $request);

    /**
     *
     */
    public function canProcessInput(): bool;
}
