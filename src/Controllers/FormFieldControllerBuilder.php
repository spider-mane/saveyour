<?php

namespace WebTheory\Saveyour\Controllers;

use Respect\Validation\Validatable;
use WebTheory\Saveyour\Contracts\DataFormatterInterface;
use WebTheory\Saveyour\Contracts\FieldDataManagerInterface;
use WebTheory\Saveyour\Contracts\FormFieldControllerInterface;
use WebTheory\Saveyour\Contracts\FormFieldInterface;

class FormFieldControllerBuilder
{
    /**
     * @var string
     */
    protected $requestVar;

    /**
     * formField
     *
     * @var FormFieldInterface
     */
    protected $formField;

    /**
     * dataManager
     *
     * @var FieldDataManagerInterface
     */
    protected $dataManager;

    /**
     * @var DataFormatterInterface
     */
    protected $formatter;

    /**
     * Callback to escape value on display
     *
     * @var callable
     */
    protected $escaper;

    /**
     * @var bool
     */
    protected $processingDisabled = false;

    /**
     * Validation rules
     *
     * @var Validatable[]
     */
    protected $rules = [];

    /**
     * Callback function(s) to sanitize incoming data
     *
     * @var array
     */
    protected $filters = [];

    /**
     * Alerts to display upon validation failure
     *
     * @var array
     */
    protected $alerts = [];

    /**
     * @var string[]
     */
    protected $mustAwait = [];

    /**
     *
     */
    public function __construct(?string $requestVar)
    {
        $requestVar && $this->requestVar = $requestVar;
    }

    /**
     *
     */
    public function create(): FormFieldControllerInterface
    {
        return new FormFieldController(
            $this->requestVar,
            $this->formField,
            $this->dataManager,
            $this->formatter,
            $this->filters,
            $this->rules,
            $this->processingDisabled,
            $this->mustAwait,
            $this->escaper
        );
    }

    /**
     * Get the value of requestVar
     *
     * @return string
     */
    public function getRequestVar(): string
    {
        return $this->requestVar;
    }

    /**
     * Set the value of requestVar
     *
     * @param string $requestVar
     *
     * @return self
     */
    public function setRequestVar(string $requestVar)
    {
        $this->requestVar = $requestVar;

        return $this;
    }

    /**
     * Get formField
     *
     * @return FormFieldInterface
     */
    public function getFormField(): FormFieldInterface
    {
        return $this->formField;
    }

    /**
     * Set formField
     *
     * @param FormFieldInterface $formField formField
     *
     * @return self
     */
    public function setFormField(FormFieldInterface $formField)
    {
        $this->formField = $formField;

        return $this;
    }

    /**
     * Get dataManager
     *
     * @return FieldDataManagerInterface
     */
    public function getDataManager(): FieldDataManagerInterface
    {
        return $this->dataManager;
    }

    /**
     * Set dataManager
     *
     * @param FieldDataManagerInterface $dataManager dataManager
     *
     * @return self
     */
    public function setDataManager(FieldDataManagerInterface $dataManager)
    {
        $this->dataManager = $dataManager;

        return $this;
    }

    /**
     * Get the value of formatter
     *
     * @return DataFormatterInterface
     */
    public function getFormatter(): DataFormatterInterface
    {
        return $this->formatter;
    }

    /**
     * Set the value of formatter
     *
     * @param DataFormatterInterface $formatter
     *
     * @return self
     */
    public function setFormatter(DataFormatterInterface $formatter)
    {
        $this->formatter = $formatter;

        return $this;
    }

    /**
     * Get callback to escape value on display
     *
     * @return callable|null
     */
    public function getEscaper(): callable
    {
        return $this->escaper;
    }

    /**
     * Set callback to escape value on display
     *
     * @param callable|null $escaper Callback to escape value on display
     *
     * @return self
     */
    public function setEscaper(callable $escaper)
    {
        $this->escaper = $escaper;

        return $this;
    }

    /**
     * Get the value of processingDisabled
     *
     * @return bool
     */
    public function getProcessingDisabled(): bool
    {
        return $this->processingDisabled;
    }

    /**
     * Set the value of processingDisabled
     *
     * @param bool $processingDisabled
     *
     * @return self
     */
    public function setProcessingDisabled(bool $processingDisabled)
    {
        $this->processingDisabled = $processingDisabled;

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
     * Get validation
     *
     * @return string
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    /**
     *
     */
    public function getRule(string $rule): Validatable
    {
        return $this->rules[$rule];
    }

    /**
     * Add validation rules
     *
     * @param array $rules Array of Validatable instances
     *
     * @return self
     */
    public function setRules(array $rules)
    {
        $this->rules = [];

        foreach ($rules as $rule => $validator) {

            if (is_array($validator)) {
                $alert = $validator['alert'] ?? null;
                $validator = $validator['validator'];
            }

            $this->addRule($rule, $validator, $alert ?? null);
        }

        return $this;
    }

    /**
     * Add validation rule
     *
     * @param string $rule Name of the the rule being checked
     * @param Validatable $validator Validatable instance
     * @param string $alert Message to be displayed if validation fails
     *
     * @return self
     */
    public function addRule(string $rule, Validatable $validator, ?string $alert = null)
    {
        $this->rules[$rule] = $validator;

        if ($alert) {
            $this->addAlert($rule, $alert);
        }

        return $this;
    }

    /**
     * Get validation_messages
     *
     * @return string
     */
    public function getAlerts(): array
    {
        return $this->alerts;
    }

    /**
     *
     */
    public function getAlert(string $alert)
    {
        return $this->alerts[$alert];
    }

    /**
     * Set validation messages
     *
     * @param string  $alerts  validation_messages
     *
     * @return self
     */
    public function setAlerts(array $alerts)
    {
        foreach ($alerts as $rule => $alert) {
            $this->addAlert($rule, $alert);
        }

        return $this;
    }

    /**
     * Set validation_messages
     *
     * @param string  $alerts  validation_messages
     *
     * @return self
     */
    public function addAlert(string $rule, string $alert)
    {
        $this->alerts[$rule] = $alert;

        return $this;
    }

    /**
     * Get the value of mustAwait
     *
     * @return string[]
     */
    public function getMustAwait(): array
    {
        return $this->mustAwait;
    }

    /**
     * Set the value of mustAwait
     *
     * @param string[] $mustAwait
     *
     * @return self
     */
    public function setMustAwait(string ...$fields)
    {
        $this->mustAwait = $fields;

        return $this;
    }

    /**
     *
     */
    public function await(string $field)
    {
        $this->mustAwait[] = $field;

        return $this;
    }
}
