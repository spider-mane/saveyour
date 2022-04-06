<?php

namespace WebTheory\Saveyour\Contracts;

interface FormShieldReportInterface
{
    public function verificationStatus(): bool;

    /**
     * @return array<int,string>
     */
    public function ruleViolations(): array;
}
