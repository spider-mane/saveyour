<?php

namespace WebTheory\Saveyour\Contracts;

use Psr\Http\Message\ServerRequestInterface;
use WebTheory\HttpPolicy\ServerRequestPolicyInterface;

interface FormShieldInterface extends ServerRequestPolicyInterface
{
    public function analyzeRequest(ServerRequestInterface $request): FormShieldReportInterface;
}
