<?php

namespace WebTheory\Saveyour\Rules;

use Respect\Validation\Validatable;

class RuleSet
{
    /**
     * @var Validatable[]
     */
    protected $rules = [];

    /**
     * Get the value of rules
     *
     * @return Validatable[]
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    /**
     *
     */
    public function add(string $name, Validatable $rule)
    {
        $this->rules[$name] = $rule;

        return $this;
    }

    /**
     * Set the value of rules
     *
     * @param array $rules
     *
     * @return self
     */
    public function extend(array $rules)
    {
        foreach ($rules as $name => $rule) {
            $this->add($name, $rule);
        }

        return $this;
    }

    /**
     *
     */
    public function get(string $rule): Validatable
    {
        return $this->rules[$rule];
    }

    /**
     *
     */
    public function break(string $rule)
    {
        unset($this->rules[$rule]);

        return $this;
    }
}
