<?php

namespace WebTheory\Saveyour\Elements;

use WebTheory\Html\AbstractHtmlElement;

class Label extends AbstractHtmlElement
{
    protected string $content;

    protected ?string $for = null;

    public function __construct(string $content)
    {
        $this->content = $content;

        parent::__construct();
    }

    /**
     * Get the value of content
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Get the value of for
     *
     * @return mixed
     */
    public function getFor()
    {
        return $this->for;
    }

    /**
     * Set the value of for
     *
     * @param mixed $for
     *
     * @return self
     */
    public function setFor($for)
    {
        $this->for = $for;

        return $this;
    }

    protected function resolveAttributes(): AbstractHtmlElement
    {
        return parent::resolveAttributes()
            ->addAttribute('for', $this->for);
    }

    protected function renderHtmlMarkup(): string
    {
        return $this->tag('label', $this->attributes, $this->content);
    }
}
