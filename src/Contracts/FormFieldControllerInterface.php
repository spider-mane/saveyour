<?php

namespace WebTheory\Saveyour\Contracts;

use LogicException;
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
     * @throws LogicException if $formField property is null
     *
     * @return FormFieldInterface FormFieldInterface instance with filled in
     * name and value
     */
    public function compose(ServerRequestInterface $request): FormFieldInterface;

    /**
     * @param ServerRequestInterface $request
     *
     * @throws LogicException if $formField property is null
     *
     * @return string FormFieldInterface instance as html string
     */
    public function render(ServerRequestInterface $request): string;

    /**
     * @param ServerRequestInterface $request
     *
     * @return ProcessedFieldReportInterface
     */
    public function process(ServerRequestInterface $request): ProcessedFieldReportInterface;

    public function requestVarPresent(ServerRequestInterface $request): bool;

    public function isPermittedToProcess(): bool;
}
