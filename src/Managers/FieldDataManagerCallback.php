<?php

namespace WebTheory\Saveyour\Managers;

use Psr\Http\Message\ServerRequestInterface;
use WebTheory\Saveyour\Contracts\FieldDataManagerInterface;

class FieldDataManagerCallback implements FieldDataManagerInterface
{
    /**
     * @var callable
     */
    private $getDataCallback;

    /**
     * @var callable
     */
    private $handleDataCallback;

    /**
     *
     */
    public function __construct(callable $getDataCallback, callable $handleDataCallback)
    {
        $this->getDataCallback = $getDataCallback;
        $this->handleDataCallback = $handleDataCallback;
    }

    /**
     *
     */
    public function getCurrentData(ServerRequestInterface $request)
    {
        return ($this->getDataCallback)($request);
    }

    /**
     *
     */
    public function handleSubmittedData(ServerRequestInterface $request, $data): bool
    {
        return ($this->handleDataCallback)($request, $data);
    }
}
