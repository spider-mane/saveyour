<?php

namespace WebTheory\Saveyour\Contracts\Report;

interface FormProcessReportInterface
{
    /**
     * @return array<string,mixed>
     */
    public function results(): array;

    public function resultFor(string $process);
}
