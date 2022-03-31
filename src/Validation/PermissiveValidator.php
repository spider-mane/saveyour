<?php

namespace WebTheory\Saveyour\Validation;

use WebTheory\Saveyour\Contracts\ValidationReportInterface;
use WebTheory\Saveyour\Contracts\ValidatorInterface;

class PermissiveValidator implements ValidatorInterface
{
    public function validate($input): bool
    {
        return true;
    }

    public function inspect($input): ValidationReportInterface
    {
        return new ValidationReport(true);
    }
}
