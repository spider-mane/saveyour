<?php

namespace WebTheory\Saveyour\Contracts;

use Respect\Validation\Validatable;

interface InputPurifierInterface
{
    /**
     * @return bool|mixed
     */
    public function filterInput($input);

    /**
     * @return array
     */
    public function getViolations(): array;

    /**
     * @return Validatable[]
     */
    public function getRules(): array;

    /**
     * @return Validatable
     */
    public function getRule(string $rule): Validatable;

    /**
     * @return callable[]
     */
    public function getFilters(): array;
}
