<?php

namespace WebTheory\Saveyour\Controllers;

use Respect\Validation\Validatable;
use WebTheory\Saveyour\Contracts\DataFormatterInterface;
use WebTheory\Saveyour\Contracts\FieldDataManagerInterface;
use WebTheory\Saveyour\Contracts\FormFieldControllerInterface;
use WebTheory\Saveyour\Contracts\FormFieldInterface;

class FormFieldControllerBuilder
{
    protected ?string $requestVar;

    /**
     * formField
     */
    protected ?FormFieldInterface $formField = null;

    /**
     * dataManager
     */
    protected ?FieldDataManagerInterface $dataManager = null;

    protected ?DataFormatterInterface $formatter = null;

    /**
     * Callback to escape value on display
     *
     * @var callable
     */
    protected $escaper;

    protected bool $processingDisabled = false;

    /**
     * Validation rules
     */
    protected ?Validatable $validator = null;

    /**
     * Callback function(s) to sanitize incoming data
     */
    protected array $filters = [];

    // /**
    //  * Alerts to display upon validation failure
    //  *
    //  * @var array
    //  */
    // protected $alerts = [];

    /**
     * @var string[]
     */
    protected array $mustAwait = [];

    public function __construct(?string $requestVar)
    {
        $requestVar && $this->requestVar = $requestVar;
    }

    public function create(): FormFieldControllerInterface
    {
        return new FormFieldController(
            $this->requestVar,
            $this->formField,
            $this->dataManager,
            $this->validator,
            $this->formatter,
            $this->filters,
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
    public function setRequestVar(string $requestVar): FormFieldControllerBuilder
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
    public function setFormField(FormFieldInterface $formField): FormFieldControllerBuilder
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
    public function setDataManager(FieldDataManagerInterface $dataManager): FormFieldControllerBuilder
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
    public function setFormatter(DataFormatterInterface $formatter): FormFieldControllerBuilder
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
    public function setEscaper(callable $escaper): FormFieldControllerBuilder
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
    public function setProcessingDisabled(bool $processingDisabled): FormFieldControllerBuilder
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
    public function setFilters(callable ...$filters): FormFieldControllerBuilder
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
    public function getValidator(): Validatable
    {
        return $this->validator;
    }

    public function setValidator(Validatable $validator): FormFieldControllerBuilder
    {
        $this->validator = $validator;

        return $this;
    }

    // /**
    //  * Add validation rules
    //  *
    //  * @param array $rules Array of Validatable instances
    //  *
    //  * @return self
    //  */
    // public function setRules(array $rules): FormFieldControllerBuilder
    // {
    //     $this->validator = [];

    //     foreach ($rules as $rule => $validator) {

    //         if (is_array($validator)) {
    //             $alert = $validator['alert'] ?? null;
    //             $validator = $validator['validator'];
    //         }

    //         $this->addRule($rule, $validator, $alert ?? null);
    //     }

    //     return $this;
    // }

    // /**
    //  * Add validation rule
    //  *
    //  * @param string $rule Name of the the rule being checked
    //  * @param Validatable $validator Validatable instance
    //  * @param string $alert Message to be displayed if validation fails
    //  *
    //  * @return self
    //  */
    // public function addRule(string $rule, Validatable $validator, ?string $alert = null)
    // {
    //     $this->validator[$rule] = $validator;

    //     if ($alert) {
    //         $this->addAlert($rule, $alert);
    //     }

    //     return $this;
    // }

    // /**
    //  * Get validation_messages
    //  *
    //  * @return string
    //  */
    // public function getAlerts(): array
    // {
    //     return $this->alerts;
    // }

    // /**
    //  *
    //  */
    // public function getAlert(string $alert)
    // {
    //     return $this->alerts[$alert];
    // }

    // /**
    //  * Set validation messages
    //  *
    //  * @param string  $alerts  validation_messages
    //  *
    //  * @return self
    //  */
    // public function setAlerts(array $alerts): FormFieldControllerBuilder
    // {
    //     foreach ($alerts as $rule => $alert) {
    //         $this->addAlert($rule, $alert);
    //     }

    //     return $this;
    // }

    // /**
    //  * Set validation_messages
    //  *
    //  * @param string  $alerts  validation_messages
    //  *
    //  * @return self
    //  */
    // public function addAlert(string $rule, string $alert)
    // {
    //     $this->alerts[$rule] = $alert;

    //     return $this;
    // }

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
    public function setMustAwait(string ...$fields): FormFieldControllerBuilder
    {
        $this->mustAwait = $fields;

        return $this;
    }

    public function await(string $field)
    {
        $this->mustAwait[] = $field;

        return $this;
    }
}
