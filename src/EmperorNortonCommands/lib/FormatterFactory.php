<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */
declare(strict_types=1);

namespace EmperorNortonCommands\lib;

use EmperorNortonCommands\lib\Internal\Formatter\GermanFormatter;
use EmperorNortonCommands\lib\Internal\Formatter\EnglishFormatter;

/**
 * @package EmperorNortonCommands\lib\Ddate
 * @api
 */
class FormatterFactory
{
    /**
     * Create Formatter for instances of DiscordianDate for a given locale.
     */
    public static function createFormatter(Locale $locale): FormatterInterface
    {
        return match ($locale) {
            Locale::English => new EnglishFormatter(),
            Locale::German => new GermanFormatter(),
        };
    }
}
