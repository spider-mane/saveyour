<?php

namespace WebTheory\Saveyour\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use WebTheory\Saveyour\Contracts\FormDataProcessorInterface;
use WebTheory\Saveyour\Contracts\FormFieldControllerInterface;
use WebTheory\Saveyour\Contracts\FormShieldInterface;
use WebTheory\Saveyour\Contracts\FormShieldReportInterface;
use WebTheory\Saveyour\Contracts\FormSubmissionManagerInterface;
use WebTheory\Saveyour\Contracts\ProcessedFieldReportInterface;
use WebTheory\Saveyour\Contracts\ProcessedFormReportBuilderInterface;
use WebTheory\Saveyour\Contracts\ProcessedFormReportInterface;
use WebTheory\Saveyour\Request;
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
            $this->processFields($request);
            $this->runProcessors($request);
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

    protected function processFields(ServerRequestInterface $request)
    {
        $this->sortFieldQueue();

        foreach ($this->fields as $field) {
            $this->reportBuilder->withFieldReport(
                $field->getRequestVar(),
                $this->processField($field, $request)
            );
        }

        return $this;
    }

    protected function sortFieldQueue()
    {
        usort($this->fields, function (FormFieldControllerInterface $a, FormFieldControllerInterface $b) {
            // because usort will not compare values that it infers from
            // previous comparisons to be equal, 0 should never be returned. all
            // that matters is that dependent fields are positioned after their
            // dependencies.
            return in_array($a->getRequestVar(), $b->mustAwait()) ? -1 : 1;
        });
    }

    protected function processField(FormFieldControllerInterface $field, ServerRequestInterface $request): ProcessedFieldReportInterface
    {
        return $this->fieldPresentInRequest($field, $request)
            ? $field->process($request)
            : new ProcessedFieldReport();
    }

    protected function runProcessors(ServerRequestInterface $request)
    {
        $fields = $this->reportBuilder->fieldReports();
        $all = array_keys($fields);

        foreach ($this->processors as $processor) {
            $results = [];

            foreach ($processor->getFields() ?? $all as $field) {
                $results[$field] = $fields[$field];
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
