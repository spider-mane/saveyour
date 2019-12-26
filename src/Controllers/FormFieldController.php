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
    private $processingDisabled = false;

    /**
     *
     */
    public function __construct(
        string $requestVar,
        ?FormFieldInterface $formField = null,
        ?FieldDataManagerInterface $dataManager = null,
        ?DataTransformerInterface $transformer = null
    ) {
        $this->requestVar = $requestVar;
        $this->transformer = $transformer ?? new LazyTransformer();

        if (isset($formField)) {
            $this->setFormField($formField);
        }

        if (isset($dataManager)) {
            $this->setDataManager($dataManager);
        }
    }

    /**
     * Get the value of postVar
     *
     * @return string
     */
    public function getRequestVar(): string
    {
        return $this->requestVar;
    }

    /**
     * Get the value of formField
     *
     * @return  mixed
     */
    public function getFormField(): ?FormFieldInterface
    {
        return $this->formField;
    }

    /**
     * Set the value of formField
     *
     * @param   mixed  $formField
     *
     * @return  self
     */
    public function setFormField(FormFieldInterface $formField)
    {
        $this->formField = $formField;

        return $this;
    }

    /**
     * Get the value of dataManager
     *
     * @return  mixed
     */
    public function getDataManager(): ?FieldDataManagerInterface
    {
        return $this->dataManager;
    }

    /**
     * Set the value of dataManager
     *
     * @param FieldDataManagerInterface  $dataManager
     *
     * @return self
     */
    public function setDataManager(FieldDataManagerInterface $dataManager)
    {
        $this->dataManager = $dataManager;

        return $this;
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
     * Get the value of transformer
     *
     * @return DataTransformerInterface
     */
    public function getTransformer(): DataTransformerInterface
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
     * Set callback to escape value on display
     *
     * @param callable|null $escape Callback to escape value on display
     *
     * @return self
     */
    public function setEscaper(?callable $escaper)
    {
        $this->escaper = $escaper;

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
     * Set the value of savingDisabled
     *
     * @param bool $savingDisabled
     *
     * @return self
     */
    public function setProcessingDisabled(bool $savingDisabled)
    {
        $this->processingDisabled = $savingDisabled;

        return $this;
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

        if ($this->canProcessInput() && false !== $filteredInput) {

            $updated = $this->dataManager->handleSubmittedData($request, $filteredInput);

            $results->withUpdateAttempted(true)->withUpdateSuccessful($updated);
        }

        return $this;
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
    private function getRawInput(ServerRequestInterface $request)
    {
        return Request::var($request, $this->requestVar);
    }

    /**
     *
     */
    public function getSanitizedInput(ServerRequestInterface $request)
    {
        return $this->filterInput($this->transformInput($this->getRawInput($request)));
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
}
