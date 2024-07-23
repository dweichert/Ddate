<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */
declare(strict_types=1);

namespace EmperorNortonCommands\lib;

/**
 * @package EmperorNortonCommands\lib\Ddate
 * @internal
 */
enum Locale: string
{
    case English = 'en';
    case German = 'de';

    public static function fromStringOrNull(string|null $value): Locale
    {
        if ($value === null) {
            return Locale::English;
        }
        return self::tryFrom($value) ?? Locale::English;
    }
}
