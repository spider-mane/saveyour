<?php

namespace WebTheory\Saveyour\Contracts;

interface ValidationReportInterface
{
    public function getStatus(): bool;

    public function getViolations(): array;
}
