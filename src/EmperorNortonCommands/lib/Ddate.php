<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

namespace EmperorNortonCommands\lib;
use DateTime;
use DateTimeZone;
use InvalidArgumentException;

/**
 * Class Ddate.
 *
 * @package EmperorNortonCommands
 */
class Ddate
{
    /**
     * Discordian date formatter factory.
     *
     * @var FormatterFactory
     */
    protected $_formatterFactory;

    /**
     * Discordian date converter.
     *
     * @var DdateConverter
     */
    protected $_converter;

    /**
     * Constructor method.
     *
     * @param DdateConverter   $converter         OPTIONAL Converts Gregorian to Discordian dates
     * @param FormatterFactory $formatterFactory  OPTIONAL Discordian date formatter
     */
    public function __construct(
        DdateConverter $converter = null,
        FormatterFactory $formatterFactory = null
    )
    {
        if (is_null($formatterFactory))
        {
            $this->_formatterFactory = new FormatterFactory();
        }
        else
        {
            $this->_formatterFactory = $formatterFactory;
        }
        if (is_null($converter))
        {
            $this->_converter = new DdateConverter();
        }
        else
        {
            $this->_converter = $converter;
        }
    }

    /**
     * Returns array of all supported format strings.
     *
     * @param  string   $locale OPTIONAL e.g. en for English, de for German, ...
     * @return string[]
     */
    public function getSupportedFormatStringFields($locale = 'en')
    {
        $formatter = $this->_formatterFactory->getFormatter($locale);
        return $formatter->getSupportedFormatStringFields();
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
        $ddate = $this->_converter->convert($dateObj);
        $formatter = $this->_formatterFactory->getFormatter($locale);
        $formatter->setFormat($format);
        return $formatter->format($ddate);
    }

    /**
     * Get date object from input.
     *
     * @param  string $date Gregorian date (dmY)
     * @return DateTime
     * @throws InvalidArgumentException
     */
    protected function getDateObject($date)
    {
        if (null === $date)
        {
            return new DateTime();
        }
        if (!is_numeric($date) && 8 !== strlen($date))
        {
            throw new InvalidArgumentException('Second argument expected to be a Gregorian date (dmY).');
        }
        list($year, $month, $day) = $this->splitIntoParts($date);
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
     * Returns array($day, $month, $year).
     *
     * @param  string $date Gregorian date (dmY)
     * @return array
     */
    protected function splitIntoParts($date)
    {
        $year = (integer)substr($date, 4, 4);
        $month = (integer)substr($date, 2, 2);
        $day = (integer)substr($date, 0, 2);
        return array($year, $month, $day);
    }
}
