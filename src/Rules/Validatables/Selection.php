<?php

namespace WebTheory\Saveyour\Rules\Validatables;

use Respect\Validation\Rules\AbstractRule;
use Respect\Validation\Validatable;

class Selection extends AbstractRule implements Validatable
{
    /**
     * @var Validatable
     */
    protected $rule;

    /**
     *
     */
    public function __construct(Validatable $rule)
    {
        $this->rule = $rule;
    }

    /**
     * @param Validatable $input
     */
    public function validate($input): bool
    {
        return '' === $input || $this->rule->validate($input);
    }
}
