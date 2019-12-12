<?php

namespace WebTheory\Saveyour\Concerns;

use WebTheory\Saveyour\Elements\Label;

trait LabelMaker
{
    /**
     *
     */
    protected function createLabel(string $content, array $options): Label
    {
        $label = new Label($content);

        foreach ($options as $option => $value) {
            $option = str_replace('_', '', $option);

            $set = 'set' . $option;
            $with = 'with' . $option;

            if (method_exists($label, $set)) {
                $label->$set($value);
            }

            if (method_exists($label, $with)) {
                $label->$with($value);
            }
        }

        return $label;
    }
}
