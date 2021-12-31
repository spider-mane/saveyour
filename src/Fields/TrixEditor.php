<?php

namespace WebTheory\Saveyour\Fields;

use WebTheory\Html\AbstractHtmlElement;
use WebTheory\Saveyour\Contracts\FormFieldInterface;

class TrixEditor extends AbstractFormField implements FormFieldInterface
{
    /**
     * @var string
     */
    protected $controlId;

    /**
     *
     */
    public function __construct(string $controlId)
    {
        $this->controlId = $controlId;

        parent::__construct();
    }

    /**
     * Get the value of controlId
     *
     * @return string
     */
    public function getControlId(): string
    {
        return $this->controlId;
    }

    /**
     *
     */
    protected function resolveAttributes(): AbstractHtmlElement
    {
        return parent::resolveAttributes()
            ->addAttribute('input', $this->controlId);
    }

    /**
     *
     */
    protected function renderHtmlMarkup(): string
    {
        $control = $this->createEditorFormControl()
            ->setName($this->name)
            ->setValue($this->value)
            ->setId($this->controlId);

        $editor = $this->tag('trix-editor', $this->attributes);

        return $control . $editor;
    }

    /**
     *
     */
    protected function createEditorFormControl(): Hidden
    {
        return new Hidden();
    }

    /**
     *
     */
    protected function defineControlId(): string
    {
        return $this->id . '-form-control';
    }
}
