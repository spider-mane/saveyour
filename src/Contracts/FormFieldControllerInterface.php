<?php

namespace WebTheory\Saveyour\Contracts;

use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Validatable;

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
