<?php

namespace WebTheory\Saveyour\Field\Element;

use WebTheory\Html\AbstractHtmlElement;
use WebTheory\Saveyour\Field\Abstracts\RendersOptionsTrait;

class Datalist extends AbstractHtmlElement
{
    use RendersOptionsTrait;

    protected function renderHtmlMarkup(): string
    {
        return $this->tag('datalist', $this->attributes, $this->renderSelection());
    }
}
