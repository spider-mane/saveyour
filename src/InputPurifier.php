<?php

namespace WebTheory\Saveyour;

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validatable;
use WebTheory\Saveyour\Contracts\InputPurifierInterface;

class InputPurifier implements InputPurifierInterface
{
    /**
     * Validation rules
     *
     * @var Validatable
     */
    protected $validator;

    /**
     * Callback function(s) to sanitize incoming data
     *
     * @var callable[]
     */
    protected $filters = [];

    /**
     * @var array
     */
    protected $violations = [];

    /**
     *
     */
    public function __construct(?Validatable $validator, callable ...$filters)
    {
        $validator && $this->validator = $validator;
        $filters && $this->filters = $filters;
    }

    /**
     * Get validation rules
     *
     * @return Validatable
     */
    public function getValidator(): Validatable
    {
        return $this->validator;
    }

    /**
     * @deprecated
     * Set validation rules
     *
     * @param Validatable $validator Validation rules
     *
     * @return self
     */
    public function setValidator(Validatable $validator)
    {
        $this->validator = $validator;

        return $this;
    }

    /**
     * Get callback function(s) to sanitize incoming data before saving to database
     *
     * @return array
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    /**
     * @deprecated
     * Set callback function(s) to sanitize incoming data before saving to database
     *
     * @param array $filters Callback function(s) to sanitize incoming data before saving to database
     *
     * @return self
     */
    public function setFilters(callable ...$filters)
    {
        $this->filters = $filters;

        return $this;
    }

    /**
     * @deprecated
     * Set callback function(s) to sanitize incoming data before saving to database
     *
     * @param callable  $filters  Callback function(s) to sanitize incoming data before saving to database
     *
     * @return self
     */
    public function addFilter(callable $filter)
    {
        $this->filters[] = $filter;

        return $this;
    }

    /**
     * Get validation_messages
     *
     * @return string
     */
    public function getAlerts(): array
    {
        return $this->violations;
    }

    /**
     *
     */
    public function filterInput($input)
    {
        $this->clearViolations();

        if (!isset($this->validator) || true === $this->validateInput($input)) {
            return $this->returnIfPassed($this->sanitizeInput($input));
        }

        return $this->returnIfFailed();
    }

    /**
     *
     */
    protected function validateInput($input)
    {
        foreach ((array) $input as $value) {
            try {
                $this->validator->assert($value);
            } catch (NestedValidationException $violation) {
                $this->handleViolation($violation);

                return false;
            }
        }

        return true;
    }

    /**
     *
     */
    protected function sanitizeInput($input)
    {
        $inputIsArray = is_array($input); // check whether original input is an array
        $input = (array) $input; // cast input to array for simplicity

        foreach ($this->filters as $filter) {
            foreach ($input as &$value) {
                $value = $filter($value);
            }
            unset($value);
        }

        return $inputIsArray ? $input : $input[0]; // return single item if original input was not an array
    }

    /**
     *
     */
    protected function returnIfPassed($input)
    {
        return $input;
    }

    /**
     *
     */
    protected function returnIfFailed()
    {
        return false;
    }

    /**
     *
     */
    protected function handleViolation(NestedValidationException $exception)
    {
        $this->violations = $exception->getMessages();
    }

    /**
     * Get validation violations
     *
     * @return array
     */
    public function getViolations(): array
    {
        return $this->violations;
    }

    /**
     *
     */
    protected function clearViolations()
    {
        $this->violations = [];
    }
}
