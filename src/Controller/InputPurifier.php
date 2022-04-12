<?php

namespace WebTheory\Saveyour\Controller;

use WebTheory\Saveyour\Contracts\Controller\InputPurifierInterface;
use WebTheory\Saveyour\Contracts\Formatting\InputFormatterInterface;
use WebTheory\Saveyour\Contracts\Validation\ValidatorInterface;

class InputPurifier implements InputPurifierInterface
{
    protected ValidatorInterface $validator;

    protected InputFormatterInterface $formatter;

    public function __construct(ValidatorInterface $validator, InputFormatterInterface $formatter)
    {
        $this->validator = $validator;
        $this->formatter = $formatter;
    }

    public function handleInput($input)
    {
        $report = $this->validator->inspect($input);

        if (true === $report->validationStatus()) {
            return $this->formatter->formatInput($input);
        }

        foreach ($report->ruleViolations() as $violation) {
            $this->handleRuleViolation($violation);
        }

        return $this->returnIfFailed();
    }

    protected function returnIfFailed()
    {
        return null;
    }

    protected function handleRuleViolation($rule): void
    {
        //
    }
}
