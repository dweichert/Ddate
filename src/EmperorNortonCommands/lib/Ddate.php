<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

namespace EmperorNortonCommands\lib;

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
     * @param FormatterFactory $formatter  Discordian date formatter
     */
    public function __construct(FormatterFactory $formatter = null)
    {
        if (is_null($formatter))
        {
            $this->_formatterFactory = new FormatterFactory();
        }
        $this->_converter = new DdateConverter();
    }

    /**
     * Returns array of all supported format strings.
     *
     * @return string[]
     */
    public function getSupportedFormatStringFields()
    {
        $formatter = $this->_formatterFactory->getFormatter('en');
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
     * @return string
     * @throws \InvalidArgumentException
     */
    public function ddate($format = null, $date = null)
    {
        $dateObj = $this->_getDateObject($date);
        $ddate = $this->_converter->convert($dateObj);
        $formatter = $this->_formatterFactory->getFormatter('en');
        $formatter->setFormat($format);
        return $formatter->format($ddate);
    }

    /**
     * Get date object from input.
     *
     * @param  string $date Gregorian date (dmY)
     * @return \DateTime
     * @throws \InvalidArgumentException
     */
    protected function _getDateObject($date)
    {
        if (null == $date)
        {
            return new \DateTime();
        }
        if (!is_numeric($date) && 8 != strlen($date))
        {
            throw new \InvalidArgumentException('Second argument expected to be a Gregorian date (dmY).');
        }
        list($year, $month, $day) = $this->_splitIntoParts($date);
        if (!checkdate($month, $day, $year))
        {
            throw new \InvalidArgumentException('Second argument expected to be a Gregorian date (dmY).');
        }
        $dateObject = new \DateTime($year . '-' . $month . '-' . $day, new \DateTimeZone('UTC'));
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
    protected function _splitIntoParts($date)
    {
        $year = (integer)substr($date, 4, 4);
        $month = (integer)substr($date, 2, 2);
        $day = (integer)substr($date, 0, 2);
        return array($year, $month, $day);
    }
}
