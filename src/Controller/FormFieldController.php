<?php

namespace WebTheory\Saveyour\Controller;

use LogicException;
use Psr\Http\Message\ServerRequestInterface;
use WebTheory\Saveyour\Contracts\Controller\FormFieldControllerInterface;
use WebTheory\Saveyour\Contracts\Data\FieldDataManagerInterface;
use WebTheory\Saveyour\Contracts\Field\FormFieldInterface;
use WebTheory\Saveyour\Contracts\Formatting\DataFormatterInterface;
use WebTheory\Saveyour\Contracts\Report\Builder\ProcessedFieldReportBuilderInterface;
use WebTheory\Saveyour\Contracts\Report\ProcessedFieldReportInterface;
use WebTheory\Saveyour\Contracts\Report\ValidationReportInterface;
use WebTheory\Saveyour\Contracts\Validation\ValidatorInterface;
use WebTheory\Saveyour\Data\LazyManager;
use WebTheory\Saveyour\Formatting\LazyDataFormatter;
use WebTheory\Saveyour\Http\Request;
use WebTheory\Saveyour\Report\Builder\ProcessedFieldReportBuilder;
use WebTheory\Saveyour\Validation\PermissiveValidator;

class FormFieldController implements FormFieldControllerInterface
{
    protected string $requestVar;

    protected ?FormFieldInterface $formField = null;

    protected FieldDataManagerInterface $dataManager;

    protected ValidatorInterface $validator;

    protected DataFormatterInterface $dataFormatter;

    protected ProcessedFieldReportBuilderInterface $cacheBuilder;

    protected bool $isPermittedToProcess = true;

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
        ?bool $processingEnabled = null,
        ?array $await = null
    ) {
        $this->requestVar = $requestVar;
        $this->formField = $formField;

        $this->dataManager = $dataManager ?? new LazyManager();
        $this->validator = $validator ?? new PermissiveValidator();
        $this->dataFormatter = $formatter ?? new LazyDataFormatter();

        $this->isPermittedToProcess = $processingEnabled ?? $this->isPermittedToProcess;

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
    public function isPermittedToProcess(): bool
    {
        return $this->isPermittedToProcess;
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
        $this->processData($request);

        return $this->cacheBuilder->build();
    }

    protected function initCacheBuilder()
    {
        $this->cacheBuilder = new ProcessedFieldReportBuilder();

        return $this;
    }

    protected function processData(ServerRequestInterface $request)
    {
        $filteredInput = $this->getUpdatedValue($request);

        $this->cacheBuilder->withSanitizedInputValue($filteredInput);

        if (false !== $filteredInput && $this->isPermittedToProcess()) {
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
        return $this->formatData($this->dataManager->getCurrentData($request));
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

    protected function formatData($data)
    {
        return $this->dataFormatter->formatData($data);
    }

    protected function formatInput($input)
    {
        return $this->dataFormatter->formatInput($input);
    }

    public function render(ServerRequestInterface $request): string
    {
        return $this->compose($request)->toHtml();
    }

    public function compose(ServerRequestInterface $request): FormFieldInterface
    {
        if (isset($this->formField)) {
            return $this->prepareFormFieldForRendering($request)->getFormField();
        }

        throw $this->noFormFieldForOperationException(__METHOD__);
    }

    protected function prepareFormFieldForRendering(ServerRequestInterface $request)
    {
        return $this->setFormFieldName()->setFormFieldValue($request);
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

    protected function noFormFieldForOperationException(string $method): LogicException
    {
        return new LogicException(
            'Instance of ' . FormFieldInterface::class . ' must be provided to perform ' . $method . ' operation.'
        );
    }
}
