<?php

namespace WebTheory\Saveyour\Contracts\Controller;

interface InputPurifierInterface
{
    public function handleInput(mixed $input): mixed;
}
