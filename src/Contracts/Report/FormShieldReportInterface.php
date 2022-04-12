<?php

namespace WebTheory\Saveyour\Contracts\Report;

interface FormShieldReportInterface
{
    public function verificationStatus(): bool;

    /**
     * @return array<int,string>
     */
    public function ruleViolations(): array;
}
