<?php

namespace WebTheory\Saveyour\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use WebTheory\Saveyour\Contracts\FormDataProcessorInterface;
use WebTheory\Saveyour\Contracts\FormFieldControllerInterface;
use WebTheory\Saveyour\Contracts\FormShieldInterface;
use WebTheory\Saveyour\Contracts\FormShieldReportInterface;
use WebTheory\Saveyour\Contracts\FormSubmissionManagerInterface;
use WebTheory\Saveyour\Contracts\ProcessedFormReportBuilderInterface;
use WebTheory\Saveyour\Contracts\ProcessedFormReportInterface;
use WebTheory\Saveyour\Contracts\ProcessedInputReportInterface;
use WebTheory\Saveyour\Http\Request;
use WebTheory\Saveyour\Shield\HolyShield;

class FormSubmissionManager implements FormSubmissionManagerInterface
{
    /**
     * @var array<int,FormFieldControllerInterface>
     */
    protected array $fields = [];

    /**
     * @var array<int,FormDataProcessorInterface>
     */
    protected array $processors = [];

    protected FormShieldInterface $shield;

    protected ProcessedFormReportBuilderInterface $reportBuilder;

    /**
     * @param array<int,FormFieldControllerInterface> $fields
     * @param array<string,FormDataProcessorInterface> $processors
     */
    public function __construct(array $fields, array $processors = [], FormShieldInterface $shield = null)
    {
        $this->fields = $fields;
        $this->processors = $processors;
        $this->shield = $shield ?? new HolyShield();
    }

    public function verify(ServerRequestInterface $request): bool
    {
        return $this->shield->approvesRequest($request);
    }

    public function validate(ServerRequestInterface $request): bool
    {
        foreach ($this->fields as $field) {
            if (!$field->validate($request)) {
                return false;
            }
        }

        return true;
    }

    public function validated(ServerRequestInterface $request): array
    {
        return $this->mapValidated($request);
    }

    public function processed(ServerRequestInterface $request): array
    {
        return $this->mapValidated(
            $request,
            fn (FormFieldControllerInterface $field) => $field->getUpdatedValue($request)
        );
    }

    public function process(ServerRequestInterface $request): ProcessedFormReportInterface
    {
        $this->initReportBuilder();

        $report = $this->analyzeRequest($request);

        if (true === $report->verificationStatus()) {
            $inputReports = $this->processFields($request);
            $this->runProcessors($request, $inputReports);
            $this->processResults($request);
        }

        return $this->reportBuilder->withShieldReport($report)->build();
    }

    protected function mapValidated(ServerRequestInterface $request, callable $callback = null): array
    {
        $mapped = [];

        foreach ($this->fields as $field) {
            if (!$this->fieldPresentInRequest($field, $request)) {
                continue;
            }

            $name = $field->getRequestVar();
            $value = $this->getFieldInputValue($field, $request);

            if ($field->validate($value)) {
                $mapped[$name] = $callback ? $callback($field) : $value;
            }
        }

        return $mapped;
    }

    protected function fieldPresentInRequest(FormFieldControllerInterface $field, ServerRequestInterface $request): bool
    {
        return Request::has($request, $field->getRequestVar());
    }

    protected function getFieldInputValue(FormFieldControllerInterface $field, ServerRequestInterface $request): string
    {
        return Request::var($request, $field->getRequestVar());
    }

    protected function initReportBuilder()
    {
        $this->reportBuilder = new ProcessedFormReportBuilder();
    }

    protected function analyzeRequest(ServerRequestInterface $request): FormShieldReportInterface
    {
        return $this->shield->analyzeRequest($request);
    }

    protected function processFields(ServerRequestInterface $request): array
    {
        $this->sortFieldQueue();

        $fields = [];

        foreach ($this->fields as $field) {
            $name = $field->getRequestVar();
            $report = $this->processField($field, $request);

            $this->reportBuilder->withInputReport($name, $report);
            $fields[$name] = $report;
        }

        return $fields;
    }

    protected function processField(FormFieldControllerInterface $field, ServerRequestInterface $request): ProcessedInputReportInterface
    {
        if (!$this->fieldPresentInRequest($field, $request)) {
            return ProcessedInputReport::voided();
        }

        $input = $this->getFieldInputValue($field, $request);
        $validation = $field->inspect($input);

        if (false === $validation->validationStatus()) {
            return ProcessedInputReport::invalid($input, $validation->ruleViolations());
        }

        if (!$field->isPermittedToProcess()) {
            return ProcessedInputReport::unprocessed($input);
        }

        return ProcessedInputReport::processed($input, $field->process($request));
    }

    protected function sortFieldQueue()
    {
        usort(
            $this->fields,
            /**
             * Because usort will not compare values that it infers from
             * previous comparisons to be equal, 0 should never be returned. all
             * that matters is that dependent entries are positioned after their
             * dependencies.
             */
            fn (
                FormFieldControllerInterface $a,
                FormFieldControllerInterface $b
            ) => in_array($a->getRequestVar(), $b->mustAwait()) ? -1 : 1
        );
    }

    protected function runProcessors(ServerRequestInterface $request, array $fields)
    {
        $all = array_keys($fields);

        foreach ($this->processors as $processor) {
            $results = [];

            foreach ($processor->getFields() ?? $all as $field) {
                $results[$field] = $fields[$field] ?? null;
            }

            $this->reportBuilder->withProcessReport(
                $processor->getName(),
                $processor->process($request, $results)
            );
        }

        return $this;
    }

    protected function processResults(ServerRequestInterface $request)
    {
        //
    }
}
