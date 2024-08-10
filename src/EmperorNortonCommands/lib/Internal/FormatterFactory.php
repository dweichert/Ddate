<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */
declare(strict_types=1);

namespace EmperorNortonCommands\lib\Internal;

use EmperorNortonCommands\lib\Internal\locale\de\StandardFormatter as GermanStandardFormatter;
use EmperorNortonCommands\lib\Internal\locale\en\StandardFormatter as EnglishStandardFormatter;

/**
 * @package EmperorNortonCommands\lib\Ddate
 * @internal
 */
class FormatterFactory
{
    /**
     * Create DiscordianDateFormatter for given locale.
     */
    public static function createFormatter(Locale $locale): FormatterInterface
    {
        return match ($locale) {
            Locale::English => new EnglishStandardFormatter(),
            Locale::German => new GermanStandardFormatter(),
        };
    }
}
