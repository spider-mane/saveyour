<?php

namespace WebTheory\Saveyour\Formatting;

use WebTheory\Saveyour\Contracts\Formatting\DataFormatterInterface;

class CallbackDataFormatter implements DataFormatterInterface
{
    /**
     * @var callable
     */
    protected $dataCallback;

    /**
     * @var callable
     */
    protected $inputCallback;

    public function __construct(callable $dataCallback = null, callable $inputCallback = null)
    {
        $this->dataCallback = $dataCallback ?? [$this, 'passThrough'];
        $this->inputCallback = $inputCallback ?? [$this, 'passThrough'];
    }

    public function formatData($value)
    {
        return ($this->dataCallback)($value);
    }

    public function formatInput($value)
    {
        return ($this->dataCallback)($value);
    }

    protected function passThrough($value)
    {
        return $value;
    }
}
