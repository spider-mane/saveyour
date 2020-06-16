<?php

namespace WebTheory\Saveyour\Controllers;

use WebTheory\Saveyour\Contracts\DataTransformerInterface;
use WebTheory\Saveyour\Contracts\FieldDataManagerInterface;
use WebTheory\Saveyour\Contracts\FormFieldInterface;

class FormFieldController extends BaseFormFieldController
{
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
     *
     */
    public function setTransformer(DataTransformerInterface $transformer)
    {
        $this->transformer = $transformer;

        return $this;
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
        $this->escaper = $escaper ?? static::LAZY_ESCAPE;

        return $this;
    }

    /**
     * Set the value of processingDisabled
     *
     * @param bool $processingDisabled
     *
     * @return self
     */
    public function setProcessingDisabled(bool $processingDisabled)
    {
        $this->processingDisabled = $processingDisabled;

        return $this;
    }
}
