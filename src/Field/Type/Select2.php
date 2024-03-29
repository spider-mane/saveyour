<?php

namespace WebTheory\Saveyour\Field\Type;

use WebTheory\Saveyour\Contracts\Field\FormFieldInterface;

class Select2 extends Select implements FormFieldInterface
{
    protected ?string $width = null;

    protected string $theme = 'default';

    protected array $config = [];

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
     * @return $this
     */
    public function setWidth(string $width): Select2
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
     * @return $this
     */
    public function setTheme(string $theme): Select2
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * @return $this
     */
    protected function resolveAttributes(): Select2
    {
        parent::resolveAttributes()
            ->addAttribute(static::EXPECTED_DATA_KEY, $this->getConfiguration())
            ->addClass(static::EXPECTED_CLASS);

        return $this;
    }

    protected function getConfiguration(): string
    {
        return json_encode(
            $this->resolveConfiguration() + $this->config,
            JSON_THROW_ON_ERROR
        );
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
