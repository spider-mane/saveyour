<?php

namespace WebTheory\Saveyour\Controllers;

use WebTheory\Saveyour\Contracts\FormDataProcessorInterface;
use WebTheory\Saveyour\Contracts\FormFieldControllerInterface;
use WebTheory\Saveyour\Contracts\FormInterface;
use WebTheory\Saveyour\Contracts\FormShieldInterface;
use WebTheory\Saveyour\Contracts\FormSubmissionManagerInterface;
use WebTheory\Saveyour\Controllers\FormSubmissionManager;

abstract class AbstractForm implements FormInterface
{
    protected function submissionManager(): FormSubmissionManagerInterface
    {
        return new FormSubmissionManager(
            $this->fields(),
            $this->processors(),
            $this->shield()
        );
    }

    protected function shield(): ?FormShieldInterface
    {
        return null;
    }

    /**
     * @return array<string,FormFieldControllerInterface>
     */
    protected function fields(): array
    {
        return [];
    }

    /**
     * @return array<string,FormDataProcessorInterface>
     */
    protected function processors(): array
    {
        return [];
    }

    abstract protected function action(): string;

    abstract protected function method(): string;
}
