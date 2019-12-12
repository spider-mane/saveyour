<?php

namespace WebTheory\Saveyour\Factories;

use Exception;
use WebTheory\GuctilityBelt\Traits\ClassResolverTrait;
use WebTheory\GuctilityBelt\Traits\SmartFactoryTrait;
use WebTheory\Html\TagSage;
use WebTheory\Saveyour\Contracts\FormFieldInterface;
use WebTheory\Saveyour\Contracts\FormFieldResolverFactoryInterface;
use WebTheory\Saveyour\Fields\Input;

class FormFieldFactory implements FormFieldResolverFactoryInterface
{
    use SmartFactoryTrait;
    use ClassResolverTrait;

    /**
     *
     */
    private $fields = [];

    /**
     *
     */
    protected $namespaces = [];

    /**
     *
     */
    protected $rules = [];

    public const NAMESPACES = [
        'webtheory.saveyour' =>  "WebTheory\\Saveyour\\Fields"
    ];

    public const FIELDS = [];

    protected const CONVENTION = null;

    /**
     *
     */
    public function __construct(array $namespaces = [], array $fields = [])
    {
        $this->namespaces = $namespaces + static::NAMESPACES;
        $this->fields = $fields + static::FIELDS;
    }

    /**
     * Get the value of managers
     *
     * @return mixed
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Set the value of managers
     *
     * @param mixed $managers
     *
     * @return self
     */
    public function addField(string $arg, string $field)
    {
        $this->fields[$arg] = $field;

        return $this;
    }

    /**
     *
     */
    public function addFields(array $fields)
    {
        $this->fields = $fields + $this->fields;

        return $this;
    }

    /**
     *
     */
    public function create(string $field, array $args = []): FormFieldInterface
    {
        $class = $this->getClass($field);

        if (isset($this->fields[$field])) {
            $field = $this->build($this->fields[$field], $args);
        } elseif (false !== $class) {
            $field = $this->build($class, $args);
        } elseif (TagSage::isIt('standard_input_type', $field)) {
            $args['type'] = $field;
            $field = $this->build(Input::class, $args);
        } else {
            throw new Exception("{$field} is not a recognized field type");
        }

        return $field;
    }
}
