<?php

namespace WebTheory\Saveyour\Factory;

use InvalidArgumentException;
use WebTheory\Factory\Core\FlexFactoryCore;
use WebTheory\Factory\Engine\FactoryEngine;
use WebTheory\Factory\Interfaces\FactoryEngineInterface;
use WebTheory\Factory\Interfaces\FixedFactoryInterface;
use WebTheory\Factory\Interfaces\FlexFactoryCoreInterface;
use WebTheory\Factory\Repository\EmptyFlexFactoryRepository;
use WebTheory\Factory\Repository\FixedFactoryRepository;
use WebTheory\Factory\Resolver\ClassResolver;
use WebTheory\Saveyour\Contracts\Factory\FormFieldResolverFactoryInterface;
use WebTheory\Saveyour\Contracts\Field\FormFieldInterface;
use WebTheory\Saveyour\Factory\Core\CoreInputResolver;

class FormFieldFactory implements FormFieldResolverFactoryInterface
{
    public const FIELD_MAP = [];

    public const FIELDS = [];

    public const NAMESPACES = [
        "WebTheory\\Saveyour\\Field",
    ];

    public const CONVENTION = null;

    protected FactoryEngineInterface $engine;

    protected FlexFactoryCoreInterface $core;

    /**
     * @param array<string,class-string<FormFieldInterface>> $map
     * @param list<class-string<FormFieldInterface>> $fields
     * @param list<string> $namespaces
     * @param array<string,FixedFactoryInterface> $factories
     */
    public function __construct(array $map = [], array $fields = [], array $namespaces = [], array $factories = [])
    {
        $resolver = new ClassResolver(
            $map + static::FIELD_MAP,
            [...$fields, ...static::NAMESPACES],
            [...$namespaces, ...static::NAMESPACES],
            static::CONVENTION
        );

        $repository = $factories
            ? new FixedFactoryRepository($factories)
            : new EmptyFlexFactoryRepository();

        $this->core = new FlexFactoryCore(
            $resolver,
            $engine = new FactoryEngine(),
            $repository,
            new CoreInputResolver($engine)
        );
    }

    public function create(string $field, array $args = []): FormFieldInterface
    {
        return $this->core->process($field, $args)
            ?: throw $this->exception($field);
    }

    protected function exception(string $field): InvalidArgumentException
    {
        return new InvalidArgumentException(
            "{$field} is not a recognized field type."
        );
    }
}
