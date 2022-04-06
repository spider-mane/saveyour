<?php

namespace WebTheory\Saveyour\Shield;

use Psr\Http\Message\ServerRequestInterface;
use WebTheory\Saveyour\Contracts\FormShieldInterface;
use WebTheory\Saveyour\Contracts\FormShieldReportInterface;

class HolyShield implements FormShieldInterface
{
    public function approvesRequest(ServerRequestInterface $request): bool
    {
        return true;
    }

    public function analyzeRequest(ServerRequestInterface $request): FormShieldReportInterface
    {
        return new FormShieldReport(true, []);
    }
}
