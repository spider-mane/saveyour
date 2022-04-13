<?php

namespace WebTheory\Saveyour\Auth;

use Psr\Http\Message\ServerRequestInterface;
use WebTheory\HttpPolicy\ServerRequestPolicyInterface;
use WebTheory\Saveyour\Contracts\Auth\FormShieldInterface;
use WebTheory\Saveyour\Contracts\Report\FormShieldReportInterface;
use WebTheory\Saveyour\Report\Builder\FormShieldReportBuilder;

class FormShield implements FormShieldInterface
{
    /**
     * @var array<int,ServerRequestPolicyInterface>
     */
    protected array $policies = [];

    /**
     * @param array<string,ServerRequestPolicyInterface> $policies
     */
    public function __construct(array $policies)
    {
        foreach ($policies as $name => $policy) {
            $this->addPolicy($name, $policy);
        }
    }

    protected function addPolicy(string $name, ServerRequestPolicyInterface $policy)
    {
        $this->policies[$name] = $policy;
    }

    public function approvesRequest(ServerRequestInterface $request): bool
    {
        return $this->analyzeRequest($request)->verificationStatus();
    }

    public function analyzeRequest(ServerRequestInterface $request): FormShieldReportInterface
    {
        $builder = new FormShieldReportBuilder();

        foreach ($this->policies as $name => $policy) {
            if (!$policy->approvesRequest($request)) {
                $status = false;
                $builder->withRuleViolation($name);
            }
        }

        $builder->withVerificationStatus($status ?? true);

        return $builder->build();
    }
}
