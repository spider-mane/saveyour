<?php

namespace WebTheory\Saveyour\Field\Type;

use WebTheory\Saveyour\Contracts\Field\FormFieldInterface;
use WebTheory\Saveyour\Field\Type\Abstracts\AbstractInput;

class Input extends AbstractInput implements FormFieldInterface
{
    /**
     * Set the value of type
     *
     * @param string  $type
     *
     * @return $this
     */
    public function setType(string $type): Input
    {
        $this->type = $type;

        return $this;
    }
}
