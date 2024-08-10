<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */
declare(strict_types=1);

namespace EmperorNortonCommands\lib;

use EmperorNortonCommands\lib\Internal\DateTimeFactory;
use EmperorNortonCommands\lib\Internal\FormatterFactory;
use EmperorNortonCommands\lib\Internal\Locale;
use InvalidArgumentException;

/**
 * Provides functionality to convert Gregorian into Discordian dates and format
 * the output according to a given format string.
 *
 * @package EmperorNortonCommands\lib\Ddate
 * @api
 */
final readonly class Ddate
{
    /**
     * Returns array of all supported format strings.
     *
     * @return array<string, string>
     */
    public function getSupportedFormatStringFields(string|null $locale = null): array
    {
        return FormatterFactory::createFormatter(Locale::fromStringOrNull($locale))->getSupportedFormatStringFields();
    }

    /**
     * Convert Gregorian to Discordian dates.
     *
     * Returns the date in Discordian date format. If called with no arguments,
     * the current system date will be used. Alternatively, a Gregorian date
     * may be specified as the second argument of the function, in form of
     * a day, month and year (dmY).
     *
     * If a format string is specified as the first argument, the Discordian
     * date will be returned in a format specified by the string. This
     * mechanism works similarly to the format string mechanism of date(), only
     * almost completely differently.
     *
     * @throws InvalidArgumentException if given Gregorian date invalid
     */
    public function ddate(string|null $format = null, string|null $date = null, string|null $locale = null): string
    {
        return FormatterFactory::createFormatter(Locale::fromStringOrNull($locale))
            ->format(
                format: $format,
                ddate: DiscordianDate::fromDateTimeInterface(
                    DateTimeFactory::createFromStringOrNull($date),
                ),
            );
    }
}
