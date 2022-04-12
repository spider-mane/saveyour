<?php

namespace WebTheory\Saveyour\Formatting;

use WebTheory\Saveyour\Contracts\DataFormatterInterface;

class CallbackDataFormatter implements DataFormatterInterface
{
    /**
     * @var callable
     */
    protected $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function formatData($value)
    {
        return ($this->callback)($value);
    }

    public function formatInput($value)
    {
        return ($this->callback)($value);
    }
}
