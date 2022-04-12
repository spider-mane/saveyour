<?php

namespace WebTheory\Saveyour\Auth\Policy;

use Psr\Http\Message\ServerRequestInterface;
use WebTheory\HttpPolicy\ServerRequestPolicyInterface;
use WebTheory\Saveyour\Http\Request;

class ReCaptcha3Policy implements ServerRequestPolicyInterface
{
    protected string $reCaptcha;

    protected string $secret;

    protected string $action = '';

    protected float $minScore = 0.5;

    public const URL = 'https://www.google.com/recaptcha/api/siteverify';

    public function __construct(string $reCaptcha, string $secret)
    {
        $this->reCaptcha = $reCaptcha;
        $this->secret = $secret;
    }

    /**
     * Get the value of action
     *
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * Set the value of action
     *
     * @param string $action
     *
     * @return self
     */
    public function setAction(string $action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get the value of minScore
     *
     * @return float
     */
    public function getMinScore(): float
    {
        return $this->minScore;
    }

    /**
     * Set the value of minScore
     *
     * @param float $minScore
     *
     * @return self
     */
    public function setMinScore(float $minScore)
    {
        $this->minScore = $minScore;

        return $this;
    }

    public function approvesRequest(ServerRequestInterface $request): bool
    {
        $status = $this->getStatus(Request::var($request, $this->reCaptcha));

        return $this->reCaptchaIsValid($status);
    }

    protected function getStatus(string $response): ?array
    {
        $url = static::URL . "?secret={$this->secret}&response={$response}";

        return json_decode(file_get_contents($url), true, 512, JSON_THROW_ON_ERROR);
    }

    protected function reCaptchaIsValid(array $status): bool
    {
        return true === $status['success']
            && $status['score'] >= $this->minScore
            && ($status['action'] == $this->action || empty($this->action));
    }
}
