<?php

namespace WebTheory\Saveyour\Validation;

use Respect\Validation\Validatable;
use WebTheory\Saveyour\Contracts\ValidationReportInterface;
use WebTheory\Saveyour\Contracts\ValidatorInterface;

class RespectValidator implements ValidatorInterface
{
    /**
     * @var Validatable[]
     */
    protected array $validatables = [];

    public function __construct(Validatable ...$validatables)
    {
        $this->validatables = $validatables;
    }

    public function validate($value): ValidationReportInterface
    {
        $builder = new ValidationReportBuilder();

        foreach ($this->validatables as $validatable) {
            if (!$validatable->validate($value)) {
                $builder->violation($validatable->getName());
                $status = false;
            }
        }

        $builder->status($status ?? true);

        return $builder->build();
    }

    public function isValid($value): bool
    {
        return $this->validate($value)->getStatus();
    }
}
