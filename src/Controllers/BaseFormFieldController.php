<?php

namespace WebTheory\Saveyour\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use WebTheory\Saveyour\Contracts\DataTransformerInterface;
use WebTheory\Saveyour\Contracts\FieldDataManagerInterface;
use WebTheory\Saveyour\Contracts\FieldOperationCacheInterface;
use WebTheory\Saveyour\Contracts\FormFieldControllerInterface;
use WebTheory\Saveyour\Contracts\FormFieldInterface;
use WebTheory\Saveyour\InputPurifier;
use WebTheory\Saveyour\Request;
use WebTheory\Saveyour\Transformers\LazyTransformer;

class BaseFormFieldController extends InputPurifier implements FormFieldControllerInterface
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
     * @var DataTransformerInterface
     */
    protected $transformer;

    /**
     * Callback to escape value on display
     *
     * @var callable|null
     */
    protected $escaper = 'htmlspecialchars';

    /**
     * @var bool
     */
    protected $processingDisabled = false;

    /**
     *
     */
    public const LAZY_ESCAPE = [self::class, 'lazyEscaper'];

    /**
     *
     */
    public function __construct(
        string $requestVar,
        ?FormFieldInterface $formField = null,
        ?FieldDataManagerInterface $dataManager = null,
        ?DataTransformerInterface $transformer = null,
        ?array $filters = null,
        ?array $rules = null,
        ?callable $escaper = null,
        ?bool $processingDisabled = null
    ) {
        $this->requestVar = $requestVar;

        $formField && $this->formField = $formField;
        $dataManager && $this->dataManager = $dataManager;
        $processingDisabled && $this->processingDisabled = $processingDisabled;
        $escaper && $this->escaper = $escaper;

        $this->transformer = $transformer ?? new LazyTransformer();

        $filters && $this->setFilters(...$filters);
        $rules && $this->setRules($rules);
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
     * Get the value of transformer
     *
     * @return DataTransformerInterface|null
     */
    public function getTransformer(): ?DataTransformerInterface
    {
        return $this->transformer;
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
        $results = new FieldOperationCacheBuilder();
        $requestVarExists = $this->requestVarExists($request);
        $results->withRequestVarPresent($requestVarExists);

        if (!$requestVarExists) {
            return $results;
        }

        $this->processData($request, $results);

        $results->withRuleViolations($this->getViolations());

        $this->clearViolations();

        return $results;
    }

    /**
     *
     */
    protected function processData(ServerRequestInterface $request, FieldOperationCacheBuilder $results)
    {
        $filteredInput = $this->getSanitizedInput($request);

        $results->withSanitizedInputValue($filteredInput);

        if (false !== $filteredInput && $this->canProcessInput()) {

            $updated = $this->processInput($request, $filteredInput);

            $results->withUpdateAttempted(true)->withUpdateSuccessful($updated);
        }

        return $this;
    }

    /**
     *
     */
    protected function processInput(ServerRequestInterface $request, $input): bool
    {
        return $this->dataManager->handleSubmittedData($request, $this->transformInput($input));
    }

    /**
     *
     */
    public function getPresetValue(ServerRequestInterface $request)
    {
        $data = $this->hasDataManager() ? $this->dataManager->getCurrentData($request) : '';

        return $this->escapeValue($this->transformData($data));
    }

    /**
     *
     */
    public function requestVarExists(ServerRequestInterface $request): bool
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
    protected function transformData($data)
    {
        return $this->transformer->transform($data);
    }

    /**
     *
     */
    protected function transformInput($input)
    {
        return $this->transformer->reverseTransform($input);
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
            ? call_user_func($this->escaper, $value)
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
        return $this
            ->prepareFormFieldForRendering($request)
            ->getFormField();
    }

    /**
     *
     */
    protected function prepareFormFieldForRendering(ServerRequestInterface $request)
    {
        return $this
            ->setFormFieldValue($request)
            ->setFormFieldName();
    }

    /**
     *
     */
    protected static function lazyEscaper($value)
    {
        return $value;
    }
}
