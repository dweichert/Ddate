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
use Stringable;

/**
 * Class Ddate.
 *
 * @package EmperorNortonCommands
 */
class Ddate
{
    /**
     * Discordian date formatter factory.
     */
    private readonly FormatterFactory $formatterFactory;

    /**
     * Discordian date converter.
     */
    private readonly Converter $converter;

    /**
     * Constructor method.
     *
     * The parameters are deliberately not type-declared: arguments of the
     * wrong type are rejected with an InvalidArgumentException, which is
     * part of the API (a type declaration would throw a TypeError instead).
     *
     * @param Converter|null        $converter         OPTIONAL Converts Gregorian to Discordian dates
     * @param FormatterFactory|null $formatterFactory  OPTIONAL Discordian date formatter
     * @throws InvalidArgumentException
     */
    public function __construct(
        $converter = null,
        $formatterFactory = null
    )
    {
        if (null !== $converter && !$converter instanceof Converter)
        {
            throw new InvalidArgumentException('First argument expected to be an instance of Converter or null.');
        }
        if (null !== $formatterFactory && !$formatterFactory instanceof FormatterFactory)
        {
            throw new InvalidArgumentException('Second argument expected to be an instance of FormatterFactory or null.');
        }
        $this->formatterFactory = $formatterFactory ?? new FormatterFactory();
        $this->converter = $converter ?? new Converter();
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
        $ddate = $this->converter->convert($dateObj);
        $formatter = $this->formatterFactory->getFormatter($locale);
        $formatter->setFormat($format);
        return $formatter->format($ddate);
    }

    /**
     * Get date object from input.
     *
     * Scalars and Stringable objects are converted to string the way 1.1
     * (without strict types) did; other values fail with the same TypeError.
     *
     * @param  mixed $date Gregorian date (dmY)
     * @throws InvalidArgumentException
     */
    private function getDateObject(mixed $date): DateTime
    {
        if (null === $date)
        {
            return new DateTime();
        }
        $string = is_scalar($date) || $date instanceof Stringable ? (string)$date : $date;
        if (!is_numeric($date) && 8 !== strlen($string))
        {
            throw new InvalidArgumentException('Second argument expected to be a Gregorian date (dmY).');
        }
        [$year, $month, $day] = $this->splitIntoParts($string);
        if (!checkdate($month, $day, $year))
        {
            throw new InvalidArgumentException('Second argument expected to be a Gregorian date (dmY).');
        }
        $dateObject = new DateTime($year . '-' . $month . '-' . $day, new DateTimeZone('UTC'));
        return $dateObject;
    }

    /**
     * Splits date string into parts.
     *
     * Returns [$year, $month, $day].
     *
     * @param  string $date Gregorian date (dmY)
     * @return int[]
     */
    private function splitIntoParts(string $date): array
    {
        $year = (int)substr($date, 4, 4);
        $month = (int)substr($date, 2, 2);
        $day = (int)substr($date, 0, 2);
        return [$year, $month, $day];
    }
}
