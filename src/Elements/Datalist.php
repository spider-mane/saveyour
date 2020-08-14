<?php

namespace WebTheory\Saveyour\Elements;

use WebTheory\Html\AbstractHtmlElement;
use WebTheory\Saveyour\Concerns\RendersOptionsTrait;

class Datalist extends AbstractHtmlElement
{
    use RendersOptionsTrait;

    /**
     *
     */
    protected function renderHtmlMarkup(): string
    {
        return $this->tag('datalist', $this->attributes, $this->renderSelection());
    }
}
