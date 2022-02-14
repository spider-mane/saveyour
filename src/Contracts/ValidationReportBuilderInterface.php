<?php

namespace WebTheory\Saveyour\Contracts;

interface ValidationReportBuilderInterface
{
    public function status(bool $validationStatus);

    public function violation(string $violation);

    public function build(): ValidationReportInterface;
}
