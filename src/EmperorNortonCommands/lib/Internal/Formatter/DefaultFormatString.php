<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */
declare(strict_types=1);

namespace EmperorNortonCommands\lib\Internal\Formatter;

use EmperorNortonCommands\lib\Locale;

/**
 * @package EmperorNortonCommands\lib\Ddate
 * @internal
 */
enum DefaultFormatString: string
{
    case English = '%{%A, %B %d,%} %Y YOLD';
    case German = '%{%A, %B %d,%} %Y n. Gre.';

    public static function fromLocale(Locale $locale): DefaultFormatString
    {
        return match ($locale) {
            Locale::English => self::English,
            Locale::German => self::German,
        };
    }
}
