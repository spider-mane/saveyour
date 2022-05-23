<?php

namespace WebTheory\Saveyour\Controller\Builder;

use WebTheory\Saveyour\Contracts\Controller\FormFieldControllerInterface;
use WebTheory\Saveyour\Contracts\Data\FieldDataManagerInterface;
use WebTheory\Saveyour\Contracts\Field\FormFieldInterface;
use WebTheory\Saveyour\Contracts\Formatting\DataFormatterInterface;
use WebTheory\Saveyour\Contracts\Validation\ValidatorInterface;
use WebTheory\Saveyour\Controller\FormFieldController;

class FormFieldControllerBuilder
{
    protected ?string $requestVar = null;

    protected ?FormFieldInterface $formField = null;

    protected ?FieldDataManagerInterface $dataManager = null;

    protected ?DataFormatterInterface $formatter = null;

    protected ?ValidatorInterface $validator = null;

    protected ?bool $isPermittedToProcess = false;

    /**
     * @var null|string[]
     */
    protected ?array $mustAwait = null;

    public function __construct(string $requestVar = null)
    {
        $requestVar && $this->requestVar = $requestVar;
    }

    /**
     * @return $this
     */
    public function requestVar(string $requestVar): FormFieldControllerBuilder
    {
        $this->requestVar = $requestVar;

        return $this;
    }

    /**
     * @return $this
     */
    public function formField(?FormFieldInterface $formField): FormFieldControllerBuilder
    {
        $this->formField = $formField;

        return $this;
    }

    /**
     * @return $this
     */
    public function dataManager(?FieldDataManagerInterface $dataManager): FormFieldControllerBuilder
    {
        $this->dataManager = $dataManager;

        return $this;
    }

    /**
     * @return $this
     */
    public function validator(?ValidatorInterface $validator): FormFieldControllerBuilder
    {
        $this->validator = $validator;

        return $this;
    }

    /**
     * @return $this
     */
    public function formatter(?DataFormatterInterface $formatter): FormFieldControllerBuilder
    {
        $this->formatter = $formatter;

        return $this;
    }

    /**
     * @return $this
     */
    public function canProcess(bool $processingDisabled): FormFieldControllerBuilder
    {
        $this->isPermittedToProcess = $processingDisabled;

        return $this;
    }

    /**
     * @return $this
     */
    public function awaitAll(array $fields): FormFieldControllerBuilder
    {
        array_map([$this, 'await'], $fields);

        return $this;
    }

    /**
     * @return $this
     */
    public function await(string $field): FormFieldControllerBuilder
    {
        $this->mustAwait[] = $field;

        return $this;
    }

    public function get(): FormFieldControllerInterface
    {
        return new FormFieldController(
            $this->requestVar,
            $this->formField,
            $this->dataManager,
            $this->validator,
            $this->formatter,
            $this->isPermittedToProcess,
            $this->mustAwait,
        );
    }

    public static function for(string $requestVar): FormFieldControllerBuilder
    {
        return new static($requestVar);
    }
}
