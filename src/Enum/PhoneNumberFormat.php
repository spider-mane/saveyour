<?php

namespace WebTheory\Saveyour\Enum;

use MyCLabs\Enum\Enum;
use libphonenumber\PhoneNumberFormat as LibPhoneNumberFormat;

/**
 * @method static int E164()
 * @method static int NATIONAL()
 * @method static int INTERNATIONAL()
 * @method static int RFC3966()
 *
 * @method static string STANDARD()
 * @method static string DASHES()
 * @method static string DOTS()
 * @method static string SPACES()
 * @method static string UNFORMATTED()
 */
class PhoneNumberFormat extends Enum
{
    private const E164 = LibPhoneNumberFormat::E164;
    private const NATIONAL = LibPhoneNumberFormat::NATIONAL;
    private const INTERNATIONAL = LibPhoneNumberFormat::INTERNATIONAL;
    private const RFC3966 = LibPhoneNumberFormat::RFC3966;

    private const STANDARD = '(';
    private const DASHES = '-';
    private const DOTS = '.';
    private const SPACES = ' ';
    private const UNFORMATTED = '';
}
