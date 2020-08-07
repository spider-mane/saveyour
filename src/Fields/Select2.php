<?php

namespace WebTheory\Saveyour\Fields;

use WebTheory\Html\AbstractHtmlElement;
use WebTheory\Saveyour\Concerns\IsSimpleSelectionFieldTrait;
use WebTheory\Saveyour\Concerns\MultiValueSelectionTrait;
use WebTheory\Saveyour\Contracts\FormFieldInterface;
use WebTheory\Saveyour\Elements\Option;

class Select2 extends Select implements FormFieldInterface
{
    use IsSimpleSelectionFieldTrait;
    use MultiValueSelectionTrait;

    /**
     * @var string
     */
    protected $width;

    /**
     * @var string
     */
    protected $dir;

    /**
     * @var bool
     */
    protected $allowClear = false;

    /**
     *
     */

    protected const EXPECTED_CLASS = 'saveyour--select2';

    /**
     * Get the value of dir
     *
     * @return string
     */
    public function getDir(): string
    {
        return $this->dir;
    }

    /**
     * Set the value of dir
     *
     * @param string $dir
     *
     * @return self
     */
    public function setDir(string $dir)
    {
        $this->dir = $dir;

        return $this;
    }

    /**
     * Get the value of width
     *
     * @return string
     */
    public function getWidth(): string
    {
        return $this->width;
    }

    /**
     * Set the value of width
     *
     * @param string $width
     *
     * @return self
     */
    public function setWidth(string $width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     *
     */
    protected function createPlaceholder(): Option
    {
        return new Option('', '');
    }

    /**
     *
     */
    protected function resolveAttributes(): AbstractHtmlElement
    {
        $this->addClass(static::EXPECTED_CLASS);

        return parent::resolveAttributes()
            ->addAttribute('data-saveyour__select2', $this->getConfiguration());
    }

    /**
     *
     */
    protected function getConfiguration(): string
    {
        return htmlspecialchars(json_encode($this->resolveConfiguration()));
    }

    /**
     *
     */
    protected function resolveConfiguration(): array
    {
        return [
            'dir' => $this->dir,
            'disabled' => $this->disabled,
            'multiple' => $this->multiple,
            'placeholder' => $this->placeholder,
            'width' => $this->width
        ];
    }
}
