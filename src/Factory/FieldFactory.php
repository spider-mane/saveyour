<?php

namespace WebTheory\Saveyour\Factory;

use InvalidArgumentException;
use WebTheory\Factory\Core\FlexFactoryCore;
use WebTheory\Factory\Engine\FactoryEngine;
use WebTheory\Factory\Interfaces\FlexFactoryCoreInterface;
use WebTheory\Factory\Repository\EmptyFlexFactoryRepository;
use WebTheory\Factory\Repository\FixedFactoryRepository;
use WebTheory\Factory\Resolver\ClassResolver;
use WebTheory\Saveyour\Contracts\Controller\FormFieldControllerInterface;
use WebTheory\Saveyour\Contracts\Factory\FormFieldControllerFactoryInterface;
use WebTheory\Saveyour\Contracts\Factory\FormFieldControllerFlexFactoryInterface;
use WebTheory\Saveyour\Controller\FormFieldController;

class FieldFactory implements FormFieldControllerFlexFactoryInterface
{
    public const CONTROLLER_MAP = [];

    public const CONTROLLERS = [];

    public const NAMESPACES = [];

    public const CONVENTION = null;

    protected FlexFactoryCoreInterface $core;

    /**
     * @param array<string,class-string<FormFieldControllerFactoryInterface>> $map
     * @param list<class-string<FormFieldControllerFactoryInterface>> $controllers
     * @param list<string> $namespaces
     * @param array<string,FormFieldControllerFactoryInterface> $factories
     */
    public function __construct(array $map = [], array $controllers = [], array $namespaces = [], array $factories = [])
    {
        $resolver = new ClassResolver(
            $map + static::CONTROLLER_MAP,
            [...$controllers, ...static::CONTROLLERS],
            [...$namespaces, static::NAMESPACES],
            static::CONVENTION
        );

        $engine = new FactoryEngine();

        $repository = new FixedFactoryRepository(
            $factories + $this->defaultFactories()
        );

        $this->core = new FlexFactoryCore($resolver, $engine, $repository);
    }

    public function create(string $manager, array $args = []): FormFieldControllerInterface
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

    protected function defaultFactories(): array
    {
        return [
            'custom' => new FormFieldControllerFactory(
                new FormFieldFactory(),
                new DataManagerFactory(),
            )
        ];
    }
}
