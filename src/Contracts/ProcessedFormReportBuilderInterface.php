<?php

namespace WebTheory\Saveyour\Contracts;

interface ProcessedFormReportBuilderInterface
{
    public function withShieldReport(FormShieldReportInterface $report): ProcessedFormReportBuilderInterface;

    public function withInputReport(string $input, ProcessedInputReportInterface $report): ProcessedFormReportBuilderInterface;

    public function withProcessReport(string $process, FormProcessReportInterface $report): ProcessedFormReportBuilderInterface;

    public function build(): ProcessedFormReportInterface;
}
