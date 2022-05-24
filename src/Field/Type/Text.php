<?php

namespace WebTheory\Saveyour\Field\Type;

use WebTheory\Saveyour\Contracts\Field\FormFieldInterface;
use WebTheory\Saveyour\Field\Type\Abstracts\AbstractInput;

class Text extends AbstractInput implements FormFieldInterface
{
    protected string $type = 'text';

    protected ?string $pattern = null;

    /**
     * @return $this
     */
    protected function resolveAttributes(): Text
    {
        parent::resolveAttributes()
            ->addAttribute('pattern', $this->pattern);

        return $this;
    }
}
