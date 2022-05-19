<?php

namespace WebTheory\Saveyour\Field;

use WebTheory\Saveyour\Contracts\Field\FormFieldInterface;
use WebTheory\Saveyour\Field\Type\Abstracts\AbstractFormField;

class DataOnly extends AbstractFormField implements FormFieldInterface
{
    protected function renderHtmlMarkup(): string
    {
        return '';
    }
}
