<?php

namespace WebTheory\Saveyour\Field\Abstracts;

use WebTheory\Html\AbstractHtmlElement;

abstract class AbstractValuableElement extends AbstractHtmlElement
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed  $value
     *
     * @return $this
     */
    public function setValue($value): AbstractValuableElement
    {
        $this->value = $value;

        return $this;
    }
}
