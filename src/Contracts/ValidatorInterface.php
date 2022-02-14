<?php

namespace WebTheory\Saveyour\Contracts;

interface ValidatorInterface
{
    public function validate($value): ValidationReportInterface;

    public function isValid($value): bool;
}
