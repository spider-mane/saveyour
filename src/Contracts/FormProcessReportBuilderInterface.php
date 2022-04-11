<?php

namespace WebTheory\Saveyour\Contracts;

interface FormProcessReportBuilderInterface
{
    public function withProcessResult(string $process, $result): FormProcessReportBuilderInterface;

    public function build(): FormProcessReportInterface;
}
