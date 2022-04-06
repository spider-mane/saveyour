<?php

namespace WebTheory\Saveyour\Contracts;

use Psr\Http\Message\ServerRequestInterface;

interface FormInterface
{
    public function getForm();

    public function getFormData(): array;

    public function getSecurityFields();

    public function getInputFields();

    public function getInputFieldsData(): array;

    public function process(ServerRequestInterface $request): ProcessedFormReportInterface;
}
