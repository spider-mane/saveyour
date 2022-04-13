<?php

namespace WebTheory\Saveyour\Auth\Policy;

use ReCaptcha\ReCaptcha;
use WebTheory\HttpPolicy\ServerRequestPolicyInterface;

class ReCaptchaConfigurationPolicy extends ReCaptchaPolicy implements ServerRequestPolicyInterface
{
    public function __construct(
        string $response,
        string $secret,
        string $hostName,
        string $action,
        ?float $minScore = 0.5,
        ?int $timeout = null
    ) {
        $this->response = $response;
        $this->reCaptcha = $reCaptcha = new ReCaptcha($secret);

        $reCaptcha->setExpectedAction($action);
        $reCaptcha->setScoreThreshold($minScore);
        $reCaptcha->setExpectedHostname($hostName);
        $reCaptcha->setChallengeTimeout($timeout);
    }

    public static function create(array $config): ReCaptchaConfigurationPolicy
    {
        return new static(
            $config['response'],
            $config['secret'],
            $config['host_name'],
            $config['action'],
            $config['min_score'] ?? 0.5,
            $config['timeout'] ?? null
        );
    }
}
