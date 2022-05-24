<?php

namespace WebTheory\Saveyour\Factory;

use Exception;
use WebTheory\Factory\Traits\ClassResolverTrait;
use WebTheory\Factory\Traits\SmartFactoryTrait;
use WebTheory\Html\TagSage;
use WebTheory\Saveyour\Contracts\Factory\FormFieldResolverFactoryInterface;
use WebTheory\Saveyour\Contracts\Field\FormFieldInterface;
use WebTheory\Saveyour\Field\Type\Input;

class FormFieldFactory implements FormFieldResolverFactoryInterface
{
    use SmartFactoryTrait;
    use ClassResolverTrait;

    private array $fields = [];

    protected array $namespaces = [];

    protected array $rules = [];

    public const NAMESPACES = [
        'webtheory.saveyour' => "WebTheory\\Saveyour\\Fields",
    ];

    public const FIELDS = [];

    protected const CONVENTION = null;

    public function __construct(array $namespaces = [], array $fields = [])
    {
        $this->namespaces = $namespaces + static::NAMESPACES;
        $this->fields = $fields + static::FIELDS;
    }

    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @return $this
     */
    public function addField(string $arg, string $field): FormFieldFactory
    {
        $this->fields[$arg] = $field;

        return $this;
    }

    /**
     * @return $this
     */
    public function addFields(array $fields): FormFieldFactory
    {
        $this->fields = $fields + $this->fields;

        return $this;
    }

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
