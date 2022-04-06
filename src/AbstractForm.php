<?php

namespace WebTheory\Saveyour;

use WebTheory\Saveyour\Contracts\FormInterface;
use WebTheory\Saveyour\Contracts\FormShieldInterface;
use WebTheory\Saveyour\Controllers\FormSubmissionManager;

abstract class AbstractForm implements FormInterface
{
    abstract protected function getAction(): string;

    abstract protected function getMethod(): string;

    abstract protected function getFieldControllers();

    protected function getFormSubmissionManager(): FormSubmissionManager
    {
        return (new FormSubmissionManager());
    }

    /**
     * @return array|FormShieldInterface[]
     */
    protected function getFormValidators(): array
    {
        return [];
    }
}
