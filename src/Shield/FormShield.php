<?php

namespace WebTheory\Saveyour\Shield;

use Psr\Http\Message\ServerRequestInterface;
use WebTheory\HttpPolicy\ServerRequestPolicyInterface;
use WebTheory\Saveyour\Contracts\FormShieldInterface;
use WebTheory\Saveyour\Contracts\FormShieldReportInterface;

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
        array_map([$this, 'addPolicy'], $policies);
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
