<?php

namespace WebTheory\Saveyour\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Validatable;
use WebTheory\Saveyour\Contracts\DataFormatterInterface;
use WebTheory\Saveyour\Contracts\FieldDataManagerInterface;
use WebTheory\Saveyour\Contracts\FieldOperationCacheBuilderInterface;
use WebTheory\Saveyour\Contracts\FieldOperationCacheInterface;
use WebTheory\Saveyour\Contracts\FormFieldControllerInterface;
use WebTheory\Saveyour\Contracts\FormFieldInterface;
use WebTheory\Saveyour\Contracts\InputFormatterInterface;
use WebTheory\Saveyour\Formatters\LazyDataFormatter;
use WebTheory\Saveyour\Formatters\LazyInputFormatter;
use WebTheory\Saveyour\InputPurifier;
use WebTheory\Saveyour\Request;

class FormFieldController extends InputPurifier implements FormFieldControllerInterface
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
    protected $dataFormatter;

    /**
     * @var InputFormatterInterface
     */
    protected $inputFormatter;

    /**
     * @var FieldOperationCacheBuilderInterface
     */
    protected $cacheBuilder;

    /**
     * Callback to escape value on display
     *
     * @var callable|null
     */
    protected $escaper;

    /**
     * @var bool
     */
    protected $processingDisabled = false;

    /**
     * @var string[]
     */
    protected $mustAwait = [];

    /**
     *
     */
    public function __construct(
        string $requestVar,
        ?FormFieldInterface $formField = null,
        ?FieldDataManagerInterface $dataManager = null,
        ?Validatable $validator = null,
        ?DataFormatterInterface $dataFormatter = null,
        ?array $filters = null,
        ?bool $processingDisabled = null,
        ?array $await = null,
        ?callable $escaper = null
    ) {
        $this->requestVar = $requestVar;

        $formField && $this->formField = $formField;
        $dataManager && $this->dataManager = $dataManager;
        $processingDisabled && $this->processingDisabled = $processingDisabled;
        $escaper && $this->escaper = $escaper;

        $this->dataFormatter = $dataFormatter ?? new LazyDataFormatter();

        $await && $this->setMustAwait(...$await);

        parent::__construct($validator, ...$filters ?? []);
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
     * Get formField
     *
     * @return FormFieldInterface|null
     */
    public function getFormField(): ?FormFieldInterface
    {
        return $this->formField;
    }

    /**
     * Get dataManager
     *
     * @return FieldDataManagerInterface|null
     */
    public function getDataManager(): ?FieldDataManagerInterface
    {
        return $this->dataManager;
    }

    /**
     * Get the value of formatter
     *
     * @return DataFormatterInterface|null
     */
    public function getDataFormatter(): ?DataFormatterInterface
    {
        return $this->dataFormatter;
    }

    /**
     * Get callback to escape value on display
     *
     * @return callable|null
     */
    public function getEscaper(): ?callable
    {
        return $this->escaper;
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
    public function process(ServerRequestInterface $request): FieldOperationCacheInterface
    {
        $this->establishCacheBuilder()->processData($request);

        $this->cacheBuilder->withRuleViolations($this->getViolations());
        $this->clearViolations();

        return $this->cacheBuilder;
    }

    /**
     *
     */
    protected function establishCacheBuilder()
    {
        $this->cacheBuilder = new FieldOperationCacheBuilder();

        return $this;
    }

    /**
     *
     */
    protected function processData(ServerRequestInterface $request)
    {
        $filteredInput = $this->getSanitizedInput($request);

        $this->cacheBuilder->withSanitizedInputValue($filteredInput);

        if (false !== $filteredInput && $this->canProcessInput()) {

            $updated = $this->processInput($request, $filteredInput);

            $this->cacheBuilder->withUpdateAttempted(true)->withUpdateSuccessful($updated);
        }

        return $this;
    }

    /**
     *
     */
    protected function processInput(ServerRequestInterface $request, $input): bool
    {
        return $this->dataManager->handleSubmittedData($request, $this->formatInput($input));
    }

    /**
     *
     */
    public function getPresetValue(ServerRequestInterface $request)
    {
        $data = $this->hasDataManager() ? $this->dataManager->getCurrentData($request) : '';

        return $this->escapeValue($this->formatData($data));
    }

    /**
     *
     */
    public function requestVarPresent(ServerRequestInterface $request): bool
    {
        return Request::has($request, $this->requestVar);
    }

    /**
     *
     */
    protected function getRawInput(ServerRequestInterface $request)
    {
        return Request::var($request, $this->requestVar);
    }

    /**
     *
     */
    public function getSanitizedInput(ServerRequestInterface $request)
    {
        return $this->filterInput($this->getRawInput($request));
    }

    /**
     *
     */
    public function canProcessInput(): bool
    {
        return $this->hasDataManager() && !$this->isProcessingDisabled();
    }

    /**
     *
     */
    protected function formatData($data)
    {
        return $this->dataFormatter->formatData($data);
    }

    /**
     *
     */
    protected function formatInput($input)
    {
        return $this->dataFormatter->formatInput($input);
    }

    /**
     *
     */
    protected function escapeValue($value)
    {
        if (!isset($this->escaper)) {
            return $value;
        }

        return !is_array($value)
            ? ($this->escaper)($value)
            : array_filter($value, $this->escaper);
    }

    /**
     *
     */
    protected function setFormFieldName()
    {
        $this->formField->setName($this->getRequestVar());

        return $this;
    }

    /**
     *
     */
    public function setFormFieldValue(ServerRequestInterface $request)
    {
        $this->formField->setValue($this->getPresetValue($request));

        return $this;
    }

    /**
     *
     */
    public function render(ServerRequestInterface $request): ?FormFieldInterface
    {
        return $this->prepareFormFieldForRendering($request)->getFormField();
    }

    /**
     *
     */
    protected function prepareFormFieldForRendering(ServerRequestInterface $request)
    {
        return $this->setFormFieldName()->setFormFieldValue($request);
    }
}
