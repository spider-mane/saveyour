<?php

namespace WebTheory\Saveyour\Contracts\Validation;

use WebTheory\Saveyour\Contracts\Report\ValidationReportInterface;

interface ValidatorInterface
{
    public function inspect($value): ValidationReportInterface;

    public function validate($value): bool;
}
