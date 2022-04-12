<?php

namespace WebTheory\Saveyour\Auth;

use Psr\Http\Message\ServerRequestInterface;
use WebTheory\Saveyour\Contracts\Auth\FormShieldInterface;
use WebTheory\Saveyour\Contracts\Report\FormShieldReportInterface;
use WebTheory\Saveyour\Report\FormShieldReport;

class HolyShield implements FormShieldInterface
{
    public function approvesRequest(ServerRequestInterface $request): bool
    {
        return true;
    }

    public function analyzeRequest(ServerRequestInterface $request): FormShieldReportInterface
    {
        return new FormShieldReport(true);
    }
}
