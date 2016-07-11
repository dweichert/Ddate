<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

namespace EmperorNortonCommands\lib;
use InvalidArgumentException;

/**
 * Class DdateFormatter.
 * @package EmperorNortonCommands\lib
 */
abstract class DdateFormatter
{
    /**
     * Returns array of all supported format strings.
     *
     * @var string[]
     */
    protected $supportedFormatStringFields = array();

    /**
     * Format string.
     *
     * @var string
     */
    protected $format = '';

    /**
     * Default format.
     *
     * @var string
     */
    protected $defaultFormat = '';

    /**
     * Holydays.
     *
     * @var Holydays
     */
    protected $holydays = null;

    /**
     * No Holyday (msgid) string.
     *
     * @var string
     */
    protected $_noHolyday = 'FNORD';

    /**
     * DdateFormatter constructor.
     * @param Holydays $holydays
     */
    public function __construct(Holydays $holydays)
    {
        $this->holydays = $holydays;
        $this->setFormat();
    }

    /**
     * Get supported format string fields.
     *
     * @return string[]
     */
    public function getSupportedFormatStringFields()
    {
        return $this->supportedFormatStringFields;
    }

    /**
     * Sets format to internal property.
     *
     * If no format string is given default format string is used as fallback.
     *
     * @param string $format OPTIONAL will use default format as fallback.
     */
    public function setFormat($format = null)
    {
        if (null === $format || (is_object($format) && !method_exists($format, '__toString')))
        {
            $format = $this->defaultFormat;
        }
        $this->format = (string)$format;
    }

    /**
     * Get formatted Discordian date.
     *
     * @param  DdateValue $ddate
     * @return string
     */
    abstract public function format(DdateValue $ddate);
    
    /**
     * Get Holyday value.
     *
     * @param DdateValue $ddate
     * @return string
     */
    protected function getHolyday(DdateValue $ddate)
    {
        return $this->holydays->getHolyday($ddate->getHolyday());
    }

    /**
     * Replaces %N and %H in given string.
     *
     * @param  string $string
     * @param  DdateValue $ddate
     * @return string
     */
    protected function replaceHolidayPlaceholders($string, DdateValue $ddate)
    {
        if (strlen($this->getHolyday($ddate)))
        {
            $string = str_replace('%N', '', $string);
            $string = str_replace('%H', $this->getHolyday($ddate), $string);
            return $string;
        }
        else
        {
            $string = preg_replace('/%N(.)*/s', '', $string);
            $string = str_replace('%H', $this->_noHolyday, $string);
            return $string;
        }
    }
}
