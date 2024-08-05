<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */
declare(strict_types=1);

namespace EmperorNortonCommands\lib;

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
     * @param  string|null               $format OPTIONAL format string
     * @param  string|null               $date   OPTIONAL Gregorian date
     * @param  string|null               $locale OPTIONAL e.g. en for English, de for German, ...
     * @return string
     * @throws InvalidArgumentException
     */
    public function ddate($format = null, string|null $date = null, string|null $locale = null)
    {
        $dateObj = DateTimeFactory::createFromStringOrNull($date);
        $discordianDate = DiscordianDate::fromDateTimeInterface($dateObj);
        $ddate = new Value(
            $discordianDate->day instanceof StTibsDay ? Value::ST_TIBS_DAY : $discordianDate->day->value,
            $discordianDate->season instanceof StTibsDay ? Value::ST_TIBS_DAY : $discordianDate->season->value,
            $discordianDate->weekday instanceof StTibsDay ? Value::ST_TIBS_DAY : $discordianDate->weekday->value,
            $discordianDate->year->value,
            XDay::daysUntilRealXDay($dateObj),
            XDay::daysUntilOriginalXDay($dateObj),
            $dateObj,
        );
        $formatter = FormatterFactory::createFormatter(Locale::fromStringOrNull($locale));
        $formatter->setFormat($format);
        return $formatter->format($ddate);
    }
}
