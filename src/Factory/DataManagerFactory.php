<?php

namespace WebTheory\Saveyour\Factory;

use Exception;
use WebTheory\Factory\Traits\ClassResolverTrait;
use WebTheory\Factory\Traits\SmartFactoryTrait;
use WebTheory\Saveyour\Contracts\Data\FieldDataManagerInterface;
use WebTheory\Saveyour\Contracts\Factory\FieldDataManagerResolverFactoryInterface;
use WebTheory\Saveyour\Data\FieldDataManagerCallback;

class DataManagerFactory implements FieldDataManagerResolverFactoryInterface
{
    use SmartFactoryTrait;
    use ClassResolverTrait;

    private array $managers = [];

    protected array $namespaces = [];

    public const NAMESPACES = [
        'webtheory.saveyour' => "WebTheory\\Saveyour\\Managers",
    ];

    public const MANAGERS = [
        'callback' => FieldDataManagerCallback::class,
    ];

    protected const CONVENTION = '%sFieldDataManager';

    public function __construct(array $namespaces = [], array $managers = [])
    {
        $this->namespaces = $namespaces + static::NAMESPACES;
        $this->managers = $managers + static::MANAGERS;
    }

    public function getManagers()
    {
        return $this->managers;
    }

    /**
     * @return $this
     */
    public function addManager(string $arg, string $manager): DataManagerFactory
    {
        $this->managers[$arg] = $manager;

        return $this;
    }

    /**
     * @return $this
     */
    public function addManagers(array $managers): DataManagerFactory
    {
        $this->managers = $managers + $this->managers;

        return $this;
    }

    public function create(string $manager, array $args = []): FieldDataManagerInterface
    {
        $manager = $this->managers[$manager] ?? null;

        if (isset($manager)) {
            $manager = $this->build($manager, $args);
        } elseif ($class = $this->getClass($manager)) {
            $manager = $this->build($class, $args);
        } else {
            throw new Exception("{$manager} is not a recognized data manager");
        }

        return $manager;
    }
}
