<?php

namespace WebTheory\Saveyour\Formatters;

use WebTheory\GuctilityBelt\Phone;
use WebTheory\Saveyour\Contracts\DataFormatterInterface;
use WebTheory\Saveyour\Enum\PhoneNumberFormat;

class PhoneNumberDataFormatter extends LazyDataFormatter implements DataFormatterInterface
{
    /**
     * @var PhoneNumberFormat
     */
    protected $format;

    /**
     *
     */
    public function __construct(PhoneNumberFormat $format)
    {
        $this->format = $format;
    }

    /**
     *
     */
    public function formatInput($value)
    {
        return Phone::formatUS($value, $this->format->getValue());
    }
}
