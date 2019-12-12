<?php

namespace WebTheory\Saveyour\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use WebTheory\Saveyour\Contracts\FieldOperationCacheInterface;
use WebTheory\Saveyour\Contracts\FormDataProcessorInterface;
use WebTheory\Saveyour\Contracts\FormFieldControllerInterface;
use WebTheory\Saveyour\Contracts\FormProcessingCacheInterface;
use WebTheory\Saveyour\Contracts\FormSubmissionManagerInterface;
use WebTheory\Saveyour\Contracts\FormValidatorInterface;

class FormSubmissionManager implements FormSubmissionManagerInterface
{
    /**
     * Array of FormFieldControllerInterface instances
     *
     * @var FormFieldControllerInterface[]
     */
    protected $fields = [];

    /**
     * Array of FormDataProcessorInterface instances
     *
     * @var FormDataProcessorInterface[]
     */
    protected $processors = [];

    /**
     * Array of form FormValidatorInterface instances
     *
     * @var FormValidatorInterface[]
     */
    protected $validators = [];

    /**
     * @var FieldOperationCacheInterface[]
     */
    protected $results = [];

    /**
     * Array of alerts to display in after form submission
     *
     * @var string[]
     */
    protected $alerts = [];

    /**
     * Get the value of validators
     *
     * @return mixed
     */
    public function getValidators()
    {
        return $this->validators;
    }

    /**
     * Set array of form FormValidatorInterface instances
     *
     * @param array $validators Array of form FormValidatorInterface instances
     *
     * @return self
     */
    public function setValidators(array $validators)
    {
        $this->validators = [];

        foreach ($validators as $rule => $validator) {

            if (is_array($validator)) {
                $alert = $validator['alert'] ?? null;
                $validator = $validator['validator'];
            }

            $this->addValidator($rule, $validator, $alert ?? null);
        }

        return $this;
    }

    /**
     *
     */
    public function addValidator(string $rule, FormValidatorInterface $validator, ?string $alert = null)
    {
        $this->validators[$rule] = $validator;

        if ($alert) {
            $this->addAlert($rule, $alert);
        }

        return $this;
    }

    /**
     * Get array of alerts to display in after form submission
     *
     * @return string[]
     */
    public function getAlerts(): array
    {
        return $this->alerts;
    }

    /**
     *
     */
    public function getAlert(string $rule): string
    {
        return $this->alerts[$rule];
    }

    /**
     * Set array of alerts to display in after form submission
     *
     * @param string[] $alert Array of alerts to display in after form submission
     *
     * @return self
     */
    public function setAlerts(array $alerts)
    {
        $this->alerts = [];

        foreach ($alerts as $rule => $alert) {
            $this->addAlert($rule, $alert);
        }
    }

    /**
     *
     */
    public function addAlert(string $rule, string $alert)
    {
        $this->alerts[$rule] = $alert;

        return $this;
    }

    /**
     * Get the value of fields
     *
     * @return mixed
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Set the value of fields
     *
     * @param FormFieldControllerInterface[] $fields
     *
     * @return self
     */
    public function setFields(FormFieldControllerInterface ...$fields)
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * @param FormFieldControllerInterface $field
     */
    public function addField(FormFieldControllerInterface $field)
    {
        $this->fields[] = $field;

        return $this;
    }

    /**
     * Get the value of groups
     *
     * @return array
     */
    public function getProcessors(): array
    {
        return $this->processors;
    }

    /**
     * Set the value of groups
     *
     * @param FormDataProcessorInterface[] $processors
     *
     * @return self
     */
    public function setProcessors(FormDataProcessorInterface ...$processors)
    {
        $this->processors = $processors;

        return $this;
    }

    /**
     * Add a group
     *
     * @param string $groups
     *
     * @return self
     */
    public function addProcessor(FormDataProcessorInterface $processor)
    {
        $this->processors[] = $processor;

        return $this;
    }

    /**
     *
     */
    public function process(ServerRequestInterface $request): FormProcessingCacheInterface
    {
        $cache = new FormProcessingCache();

        if ($this->isSafe($request, $cache)) {
            $this->processFields($request, $cache);
            $this->runProcessors($request, $cache);
            $this->processResults($request, $cache);
        }

        return $cache;
    }

    /**
     *
     */
    protected function isSafe(ServerRequestInterface $request, FormProcessingCache $cache): bool
    {
        $violations = [];

        foreach ($this->validators as $rule => $validator) {
            if (!$validator->isValid($request)) {
                $violations[$rule] = $this->alerts[$rule] ?? '';
            }
        }

        $cache->withRequestViolations($violations);

        return !empty($violations) ? false : true;
    }

    /**
     *
     */
    protected function processFields(ServerRequestInterface $request, FormProcessingCache $cache)
    {
        $inputResults = [];
        $inputViolations = [];

        foreach ($this->fields as $field) {
            $name = $field->getRequestVar();
            $operations = $field->process($request);

            $operations = new FieldOperationCache(
                $operations->requestVarPresent(),
                $operations->sanitizedInputValue(),
                $operations->updateAttempted(),
                $operations->updateSuccessful(),
                $operations->ruleViolations()
            );

            $inputResults[$name] = $operations;
            $inputViolations[$name] = $operations->ruleViolations();
        }

        $cache->withInputResults($inputResults);
        $cache->withInputViolations(array_filter($inputViolations));

        return $this;
    }

    /**
     *
     */
    protected function runProcessors(ServerRequestInterface $request, FormProcessingCache $cache)
    {
        $fields = $cache->inputResults();
        $responses = [];

        foreach ($this->processors as $processor) {
            $results = [];

            foreach ($processor->getFields() as $field) {
                $results[$field] = $fields[$field];
            }

            $responses[] = $processor->process($request, $results);
        }

        $cache->withProcessingResults(array_filter($responses));

        return $this;
    }

    /**
     *
     */
    protected function processResults(ServerRequestInterface $request, FormProcessingCache $cache)
    {
        return $this;
    }
}
