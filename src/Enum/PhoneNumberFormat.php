<?php

namespace WebTheory\Saveyour\Enum;

use MyCLabs\Enum\Enum;

/**
 * @method static string Standard()
 * @method static string Dashes()
 * @method static string Dots()
 * @method static string Spaces()
 * @method static string Unformatted()
 */
class PhoneNumberFormat extends Enum
{
    public const Standard = 'standard';
    public const Dashes = 'dashes';
    public const Dots = 'dots';
    public const Spaces = 'spaces';
    public const Unformatted = 'unformatted';
}
