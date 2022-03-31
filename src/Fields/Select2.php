<?php

namespace WebTheory\Saveyour\Fields;

use WebTheory\Html\AbstractHtmlElement;
use WebTheory\Saveyour\Contracts\FormFieldInterface;

class Select2 extends Select implements FormFieldInterface
{
    /**
     * @var string
     */
    protected $width;

    /**
     * @var string
     */
    protected $theme = 'default';

    protected $config = [];

    public const EXPECTED_CLASS = 'saveyour--select2';

    public const EXPECTED_DATA_KEY = 'data-saveyour__select2';

    public function __construct(array $config = [])
    {
        $this->config = $config + $this->config;

        parent::__construct();
    }

    /**
     * Get the value of config
     *
     * @return mixed
     */
    public function getConfig()
    {
        return $this->config;
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
     * Get the value of theme
     *
     * @return string
     */
    public function getTheme(): string
    {
        return $this->theme;
    }

    /**
     * Set the value of theme
     *
     * @param string $theme
     *
     * @return self
     */
    public function setTheme(string $theme)
    {
        $this->theme = $theme;

        return $this;
    }

    protected function resolveAttributes(): AbstractHtmlElement
    {
        $this->addClass(static::EXPECTED_CLASS);

        return parent::resolveAttributes()
            ->addAttribute(static::EXPECTED_DATA_KEY, $this->getConfiguration());
    }

    protected function getConfiguration(): string
    {
        return json_encode($this->resolveConfiguration() + $this->config);
    }

    protected function resolveConfiguration(): array
    {
        return [
            'disabled' => $this->disabled,
            'multiple' => $this->multiple,
            'placeholder' => $this->placeholder,
            'theme' => $this->theme,
            'width' => $this->width,
        ];
    }
}
