<?php

namespace WebTheory\Saveyour\Field\Type;

use WebTheory\Saveyour\Contracts\Field\FormFieldInterface;
use WebTheory\Saveyour\Field\Type\Abstracts\AbstractFormField;

class TrixEditor extends AbstractFormField implements FormFieldInterface
{
    protected string $controlId;

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
     * @return $this
     */
    protected function resolveAttributes(): TrixEditor
    {
        parent::resolveAttributes()
            ->addAttribute('input', $this->controlId);

        return $this;
    }

    protected function renderHtmlMarkup(): string
    {
        $control = $this->createEditorFormControl()
            ->setName($this->name)
            ->setValue($this->value)
            ->setId($this->controlId);

        $editor = $this->tag('trix-editor', $this->attributes);

        return $control . $editor;
    }

    protected function createEditorFormControl(): Hidden
    {
        return new Hidden();
    }

    protected function defineControlId(): string
    {
        return $this->id . '-form-control';
    }
}
