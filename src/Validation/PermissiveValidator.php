<?php

namespace WebTheory\Saveyour\Validation;

use WebTheory\Saveyour\Contracts\Report\ValidationReportInterface;
use WebTheory\Saveyour\Contracts\Validation\ValidatorInterface;
use WebTheory\Saveyour\Report\ValidationReport;

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
