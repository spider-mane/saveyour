<?php

namespace WebTheory\Saveyour\Factory;

use InvalidArgumentException;
use WebTheory\Factory\Core\CoreEngine;
use WebTheory\Factory\Core\FlexFactoryCore;
use WebTheory\Factory\Engine\FactoryEngine;
use WebTheory\Factory\Interfaces\FlexFactoryCoreInterface;
use WebTheory\Factory\Repository\EmptyFlexFactoryRepository;
use WebTheory\Factory\Repository\FixedFactoryRepository;
use WebTheory\Factory\Resolver\ClassResolver;
use WebTheory\Saveyour\Contracts\Data\FieldDataManagerInterface;
use WebTheory\Saveyour\Contracts\Factory\FieldDataManagerResolverFactoryInterface;
use WebTheory\Saveyour\Data\FieldDataManagerCallback;
use WebTheory\Saveyour\Factory\Core\CoreInputResolver;

class DataManagerFactory implements FieldDataManagerResolverFactoryInterface
{
    public const MANAGER_MAP = [
        'callback' => FieldDataManagerCallback::class,
    ];

    public const MANAGERS = [];

    public const NAMESPACES = [
        "WebTheory\\Saveyour\\Data",
    ];

    public const CONVENTION = '%sDataManager';

    protected FlexFactoryCoreInterface $core;

    /**
     * @param array<string,class-string<FieldDataManagerInterface>> $map
     * @param list<class-string<FieldDataManagerInterface>> $fields
     * @param list<string> $namespaces
     * @param array<string,FixedFactoryInterface> $factories
     */
    public function __construct(array $map = [], array $managers = [], array $namespaces = [], array $factories = [])
    {
        $resolver = new ClassResolver(
            $map + static::MANAGER_MAP,
            [...$managers, ...static::MANAGERS],
            [...$namespaces, static::NAMESPACES],
            static::CONVENTION
        );

        $engine = new FactoryEngine();

        $repository = $factories
            ? new FixedFactoryRepository($factories)
            : new EmptyFlexFactoryRepository();

        $this->core = new FlexFactoryCore($resolver, $engine, $repository);
    }

    public function create(string $manager, array $args = []): FieldDataManagerInterface
    {
        return $this->core->process($manager, $args)
            ?: throw $this->exception($manager);
    }

    protected function exception(string $manager): InvalidArgumentException
    {
        return new InvalidArgumentException(
            "{$manager} is not a recognized data manager."
        );
    }
}
