<?php

namespace WebTheory\Saveyour\Contracts;

interface ValidatorInterface
{
    public function inspect($value): ValidationReportInterface;

    public function validate($value): bool;
}
