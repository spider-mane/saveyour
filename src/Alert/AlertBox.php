<?php

namespace WebTheory\Saveyour\Alert;

class AlertBox
{
    /**
     * @var array
     */
    protected $alerts = [];

    /**
     * Get the value of alerts
     *
     * @return array
     */
    public function getAlerts(): array
    {
        return $this->alerts;
    }

    protected function add(string $name, string $alert)
    {
        $this->alerts[$name] = $alert;

        return $this;
    }

    protected function extend(array $alerts)
    {
        foreach ($alerts as $name => $alert) {
            $this->add($name, $alert);
        }

        return $this;
    }

    protected function get(string $alert): string
    {
        return $this->alerts[$alert];
    }

    protected function rebuke(string $alert)
    {
        unset($this->alerts[$alert]);

        return $this;
    }
}
