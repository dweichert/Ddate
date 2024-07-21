<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */
declare(strict_types=1);

namespace EmperorNortonCommands\lib;

use DateTime;
use DateTimeZone;
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
    private FormatterFactory $formatterFactory;

    public function __construct() {
        $this->formatterFactory = new FormatterFactory();
    }

    /**
     * Returns array of all supported format strings.
     *
     * @param  string   $locale OPTIONAL e.g. en for English, de for German, ...
     * @return string[]
     */
    public function getSupportedFormatStringFields($locale = 'en')
    {
        return $this->formatterFactory->getFormatter($locale)->getSupportedFormatStringFields();
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
     * @param  string                    $format OPTIONAL format string
     * @param  string                    $date   OPTIONAL Gregorian date
     * @param  string                    $locale OPTIONAL e.g. en for English, de for German, ...
     * @return string
     * @throws InvalidArgumentException
     */
    public function ddate($format = null, $date = null, $locale = 'en')
    {
        $dateObj = $this->getDateObject($date);
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
        $formatter = $this->formatterFactory->getFormatter($locale);
        $formatter->setFormat($format);
        return $formatter->format($ddate);
    }

    /**
     * Get date object from input.
     *
     * @throws InvalidArgumentException|\Exception
     */
    private function getDateObject(string|int|null $date): DateTime
    {
        if (null === $date) {
            return new DateTime();
        }
        if (!is_numeric($date) && 8 !== strlen($date)) {
            throw new InvalidArgumentException('Second argument expected to be a Gregorian date (dmY).');
        }
        $date = (string) $date;
        list($year, $month, $day) = $this->splitIntoParts($date);
        if (!checkdate($month, $day, $year)) {
            throw new InvalidArgumentException('Second argument expected to be a Gregorian date (dmY).');
        }
        return new DateTime($year . '-' . $month . '-' . $day, new DateTimeZone('UTC'));
    }

    /**
     * Splits date string into parts.
     *
     * Returns array($day, $month, $year).
     *
     * @param  string $date Gregorian date (dmY)
     * @return array
     */
    private function splitIntoParts(string $date): array
    {
        $year = (int)substr($date, 4, 4);
        $month = (int)substr($date, 2, 2);
        $day = (int)substr($date, 0, 2);
        return array($year, $month, $day);
    }
}
