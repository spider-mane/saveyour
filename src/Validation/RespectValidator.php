<?php

namespace WebTheory\Saveyour\Validation;

use Respect\Validation\Validatable;
use WebTheory\Saveyour\Contracts\ValidationReportInterface;
use WebTheory\Saveyour\Contracts\ValidatorInterface;
use WebTheory\Saveyour\Report\Builder\ValidationReportBuilder;

class RespectValidator implements ValidatorInterface
{
    /**
     * @var array<int,Validatable>
     */
    protected array $validatables = [];

    public function __construct(Validatable ...$validatables)
    {
        $this->validatables = $validatables;
    }

    public function inspect($value): ValidationReportInterface
    {
        $builder = new ValidationReportBuilder();

        foreach ($this->validatables as $validatable) {
            if (!$validatable->validate($value)) {
                $builder->withRuleViolation($validatable->getName());
                $status = false;
            }
        }

        $builder->withValidationStatus($status ?? true);

        return $builder->build();
    }

    public function validate($value): bool
    {
        return $this->inspect($value)->validationStatus();
    }
}
