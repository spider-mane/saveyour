<?php

namespace WebTheory\Saveyour\Contracts\Field\Element;

use JsonSerializable;

interface JsonAttributeInterface extends JsonSerializable
{
    public function get(): array;

    public function setValue(string $property, string $value);

    public function getValue(string $property): string;

    public function toJson(): string;
}
