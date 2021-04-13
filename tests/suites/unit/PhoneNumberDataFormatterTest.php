<?php

use PHPUnit\Framework\TestCase;
use WebTheory\GuctilityBelt\Phone;
use WebTheory\Saveyour\Enum\PhoneNumberFormat;
use WebTheory\Saveyour\Formatters\PhoneNumberDataFormatter;

class PhoneNumberDataFormatterTest extends TestCase
{
    /**
     * @test
     */
    public function formats_input()
    {
        $formatter = new PhoneNumberDataFormatter(PhoneNumberFormat::UNFORMATTED());

        $phone = '(202) 555-0144';
        $dashes = Phone::formatUS($phone, '');

        $this->assertEquals($dashes, $formatter->formatInput($phone));
    }
}
