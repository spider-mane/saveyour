<?php

namespace WebTheory\Saveyour\Contracts\Auth;

use Psr\Http\Message\ServerRequestInterface;
use WebTheory\HttpPolicy\ServerRequestPolicyInterface;
use WebTheory\Saveyour\Contracts\Report\FormShieldReportInterface;

interface FormShieldInterface extends ServerRequestPolicyInterface
{
    public function analyzeRequest(ServerRequestInterface $request): FormShieldReportInterface;
}
