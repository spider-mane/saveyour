<?php

namespace WebTheory\Saveyour\Factories;

use Exception;
use WebTheory\GuctilityBelt\Traits\ClassResolverTrait;
use WebTheory\GuctilityBelt\Traits\SmartFactoryTrait;
use WebTheory\Saveyour\Contracts\FieldDataManagerInterface;
use WebTheory\Saveyour\Contracts\FieldDataManagerResolverFactoryInterface;
use WebTheory\Saveyour\Managers\FieldDataManagerCallback;

class DataManagerFactory implements FieldDataManagerResolverFactoryInterface
{
    use SmartFactoryTrait;
    use ClassResolverTrait;

    /**
     *
     */
    private $managers = [];

    /**
     *
     */
    protected $namespaces = [];

    public const NAMESPACES = [
        'webtheory.saveyour' =>  "WebTheory\\Saveyour\\Managers",
    ];

    public const MANAGERS = [
        'callback' => FieldDataManagerCallback::class
    ];

    protected const CONVENTION = '%sFieldDataManager';

    /**
     *
     */
    public function __construct(array $namespaces = [], array $managers = [])
    {
        $this->namespaces = $namespaces + static::NAMESPACES;
        $this->managers = $managers + static::MANAGERS;
    }

    /**
     * Get the value of managers
     *
     * @return mixed
     */
    public function getManagers()
    {
        return $this->managers;
    }

    /**
     * Set the value of managers
     *
     * @param mixed $managers
     *
     * @return self
     */
    public function addManager(string $arg, string $manager)
    {
        $this->managers[$arg] = $manager;

        return $this;
    }

    /**
     *
     */
    public function addManagers(array $managers)
    {
        $this->managers = $managers + $this->managers;

        return $this;
    }

    /**
     *
     */
    public function create(string $manager, array $args = []): FieldDataManagerInterface
    {
        $manager = $this->managers[$manager] ?? null;

        if (isset($manager)) {
            $manager = $this->build($manager, $args);
        } elseif ($class = $this->getClass($manager)) {
            $manager = $this->build($class, $args);
        } else {
            throw new Exception("{$manager} is not a recognized field data manager");
        }

        return $manager;
    }
}
