<?php

namespace WebTheory\Saveyour\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use WebTheory\Saveyour\Contracts\DataFormatterInterface;
use WebTheory\Saveyour\Contracts\FieldDataManagerInterface;
use WebTheory\Saveyour\Contracts\FormFieldControllerInterface;
use WebTheory\Saveyour\Contracts\FormFieldInterface;
use WebTheory\Saveyour\Contracts\ProcessedFieldReportBuilderInterface;
use WebTheory\Saveyour\Contracts\ProcessedFieldReportInterface;
use WebTheory\Saveyour\Contracts\ValidationReportInterface;
use WebTheory\Saveyour\Contracts\ValidatorInterface;
use WebTheory\Saveyour\Formatters\LazyDataFormatter;
use WebTheory\Saveyour\Request;
use WebTheory\Saveyour\Validation\PermissiveValidator;

class FormFieldController implements FormFieldControllerInterface
{
    protected string $requestVar;

    protected ?FormFieldInterface $formField = null;

    protected ?FieldDataManagerInterface $dataManager = null;

    protected ValidatorInterface $validator;

    protected DataFormatterInterface $dataFormatter;

    protected ProcessedFieldReportBuilderInterface $cacheBuilder;

    protected bool $processingDisabled = false;

    /**
     * @var array<int,string>
     */
    protected array $mustAwait = [];

    public function __construct(
        string $requestVar,
        ?FormFieldInterface $formField = null,
        ?FieldDataManagerInterface $dataManager = null,
        ?ValidatorInterface $validator = null,
        ?DataFormatterInterface $formatter = null,
        ?bool $processingDisabled = null,
        ?array $await = null
    ) {
        $this->requestVar = $requestVar;
        $this->formField = $formField;
        $this->dataManager = $dataManager;

        $this->validator = $validator ?? new PermissiveValidator();
        $this->dataFormatter = $formatter ?? new LazyDataFormatter();

        $this->processingDisabled = $processingDisabled ?? $this->processingDisabled;

        $await && $this->setMustAwait(...$await);
    }

    public function getRequestVar(): string
    {
        return $this->requestVar;
    }

    public function getFormField(): ?FormFieldInterface
    {
        return $this->formField;
    }

    public function getDataManager(): ?FieldDataManagerInterface
    {
        return $this->dataManager;
    }

    public function getDataFormatter(): ?DataFormatterInterface
    {
        return $this->dataFormatter;
    }

    /**
     * Get hasDataManager
     *
     * @return bool
     */
    public function hasDataManager(): bool
    {
        return isset($this->dataManager);
    }

    /**
     * Get the value of mustAwait
     *
     * @return string[]
     */
    public function mustAwait(): array
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
    protected function setMustAwait(string ...$fields)
    {
        $this->mustAwait = $fields;

        return $this;
    }

    /**
     * Get the value of savingDisabled
     *
     * @return bool
     */
    public function isProcessingDisabled(): bool
    {
        return $this->processingDisabled;
    }

    /**
     * {@inheritDoc}
     */
    public function inspect($value): ValidationReportInterface
    {
        return $this->validator->inspect($value);
    }

    /**
     * {@inheritDoc}
     */
    public function validate($value): bool
    {
        return $this->validator->validate($value);
    }

    /**
     * {@inheritDoc}
     */
    public function process(ServerRequestInterface $request): ProcessedFieldReportInterface
    {
        $this->initCacheBuilder();

        if ($this->validateInput($request)) {
            $this->processData($request);
        }

        return $this->cacheBuilder->build();
    }

    protected function initCacheBuilder()
    {
        $this->cacheBuilder = new ProcessedFieldReportBuilder();

        return $this;
    }

    protected function validateInput(ServerRequestInterface $request): bool
    {
        $report = $this->inspect($this->getRawInput($request));
        $status = $report->validationStatus();

        $this->cacheBuilder
            ->withValidationStatus($status)
            ->withRuleViolations($report->ruleViolations());

        return $status;
    }

    protected function processData(ServerRequestInterface $request)
    {
        $filteredInput = $this->getUpdatedValue($request);

        $this->cacheBuilder->withSanitizedInputValue($filteredInput);

        if (false !== $filteredInput && $this->canProcessInput()) {
            $updated = $this->processInput($request, $filteredInput);

            $this->cacheBuilder->withUpdateAttempted(true)->withUpdateSuccessful($updated);
        }

        return $this;
    }

    protected function processInput(ServerRequestInterface $request, $input): bool
    {
        return $this->dataManager->handleSubmittedData($request, $this->formatInput($input));
    }

    public function getPresetValue(ServerRequestInterface $request)
    {
        $data = $this->hasDataManager() ? $this->dataManager->getCurrentData($request) : '';

        return $this->formatData($data);
    }

    public function requestVarPresent(ServerRequestInterface $request): bool
    {
        return Request::has($request, $this->requestVar);
    }

    protected function getRawInput(ServerRequestInterface $request)
    {
        return Request::var($request, $this->requestVar);
    }

    public function getUpdatedValue(ServerRequestInterface $request)
    {
        return $this->formatInput($this->getRawInput($request));
    }

    public function canProcessInput(): bool
    {
        return $this->hasDataManager() && !$this->isProcessingDisabled();
    }

    protected function formatData($data)
    {
        return $this->dataFormatter->formatData($data);
    }

    protected function formatInput($input)
    {
        return $this->dataFormatter->formatInput($input);
    }

    protected function setFormFieldName()
    {
        $this->formField->setName($this->getRequestVar());

        return $this;
    }

    public function setFormFieldValue(ServerRequestInterface $request)
    {
        $this->formField->setValue($this->getPresetValue($request));

        return $this;
    }

    public function render(ServerRequestInterface $request): ?FormFieldInterface
    {
        return $this->prepareFormFieldForRendering($request)->getFormField();
    }

    protected function prepareFormFieldForRendering(ServerRequestInterface $request)
    {
        return $this->setFormFieldName()->setFormFieldValue($request);
    }
}
