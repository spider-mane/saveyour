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
    public function getAlerts(): array;

    /**
     * @return Validatable
     */
    public function getValidator(): Validatable;

    /**
     * @return callable[]
     */
    public function getFilters(): array;
}
