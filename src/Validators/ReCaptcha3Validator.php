<?php

namespace WebTheory\Saveyour\Validators;

use Psr\Http\Message\ServerRequestInterface;
use WebTheory\Saveyour\Contracts\FormValidatorInterface;
use WebTheory\Saveyour\Request;

class ReCaptcha3Validator implements FormValidatorInterface
{
    /**
     *
     */
    protected $reCaptcha;

    /**
     *
     */
    protected $secret;

    /**
     * @var string
     */
    protected $action = '';

    /**
     * @var float
     */
    protected $minScore = 0.5;

    public const URL = 'https://www.google.com/recaptcha/api/siteverify';

    /**
     *
     */
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

    /**
     *
     */
    public function isValid(ServerRequestInterface $request): bool
    {
        $response = Request::var($request, $this->reCaptcha);

        $url = static::URL . "?secret={$this->secret}&response={$response}";

        $status = json_decode(file_get_contents($url), true);

        if (
            true === $status['success']
            && $status['score'] >= $this->minScore
            && ($status['action'] == $this->action || empty($this->action))
        ) {
            return true;
        }

        return false;
    }
}
