<?php

namespace WebTheory\Saveyour\Controller;

use WebTheory\Saveyour\Contracts\Controller\InputPurifierInterface;
use WebTheory\Saveyour\Contracts\Formatting\InputFormatterInterface;
use WebTheory\Saveyour\Contracts\Validation\ValidationProcessorInterface;
use WebTheory\Saveyour\Contracts\Validation\ValidatorInterface;

class InputPurifier implements InputPurifierInterface
{
    protected ValidatorInterface $validator;

    protected InputFormatterInterface $formatter;

    protected ValidationProcessorInterface $processor;

    public function __construct(
        ValidatorInterface $validator,
        InputFormatterInterface $formatter,
        ValidationProcessorInterface $processor
    ) {
        $this->validator = $validator;
        $this->formatter = $formatter;
        $this->processor = $processor;
    }

    public function handleInput(mixed $input): mixed
    {
        $report = $this->validator->inspect($input);

        if (true === $report->validationStatus()) {
            return $this->formatter->formatInput($input);
        }

        foreach ($report->ruleViolations() as $violation) {
            $this->processor->handleRuleViolation($violation);
        }

        return $this->processor->returnOnFailure();
    }
}
