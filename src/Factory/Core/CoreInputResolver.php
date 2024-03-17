<?php

namespace WebTheory\Saveyour\Factory\Core;

use WebTheory\Factory\Interfaces\FactoryEngineInterface;
use WebTheory\Factory\Interfaces\FlexFactoryCoreInterface;
use WebTheory\Html\TagSage;
use WebTheory\Saveyour\Field\Type\Input;

class CoreInputResolver implements FlexFactoryCoreInterface
{
    public function __construct(protected FactoryEngineInterface $engine)
    {
        //
    }

    public function process(string $query, array $args = []): Input|false
    {
        if (!$this->isStandardInputType($query)) {
            return false;
        }

        return $this->createInput($query, $args);
    }

    protected function isStandardInputType(string $query): bool
    {
        return TagSage::isIt('standard_input_type', $query);
    }

    protected function createInput(string $type, array $args): Input
    {
        $args['type'] = $type;

        return $this->engine->generate(Input::class, $args);
    }
}
