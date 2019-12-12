<?php

namespace WebTheory\Saveyour;

use Psr\Http\Message\ServerRequestInterface;
use WebTheory\Saveyour\Contracts\FormInterface;
use WebTheory\Saveyour\Contracts\FormSubmissionManagerInterface;
use WebTheory\Saveyour\Contracts\FormValidatorInterface;
use WebTheory\Saveyour\Controllers\FormSubmissionManager;

abstract class AbstractForm implements FormInterface
{
    /**
     *
     */
    abstract protected function getAction(): string;

    /**
     *
     */
    abstract protected function getMethod(): string;

    /**
     *
     */
    abstract protected function getFieldControllers();

    /**
     *
     */
    protected function getFormSubmissionManager(): FormSubmissionManager
    {
        return (new FormSubmissionManager())
            ->setValidators($this->getFormValidators());
    }

    /**
     * @return array|FormValidatorInterface[]
     */
    protected function getFormValidators(): array
    {
        return [];
    }
}
