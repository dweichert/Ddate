<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

namespace EmperorNortonCommands\lib;

use EmperorNortonCommands\lib\Holydays\StandardHolydays;

/**
 * Class Formatter.
 * @package EmperorNortonCommands\lib
 */
abstract class Formatter
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
     * @var Holydays[]
     */
    protected $holydays = null;

    /**
     * No Holyday (msgid) string.
     *
     * @var string
     */
    protected $noHolyday = 'FNORD';

    /**
     * DdateFormatter constructor.
     */
    public function __construct()
    {
        $this->setFormat();
        $this->holydays[StandardHolydays::getKey()] = new StandardHolydays();
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
     * @param  Value $ddate
     * @return string
     */
    abstract public function format(Value $ddate);
    
    /**
     * Get Holyday value.
     *
     * @param  Value  $ddate
     * @param  string $locale
     * @return string
     */
    protected function getHolyday(Value $ddate, $locale)
    {
        $holydays = array();
        foreach ($this->holydays as $holyday)
        {
            $holydays[] = $holyday->getHolyday($ddate, $locale);
        }

        if (empty($holydays)) {
            return '';
        }

        return $holydays[0];
    }

    /**
     * Replaces %N and %H in given string.
     *
     * @param  string $string
     * @param  Value  $ddate
     * @param  string $locale
     * @return string
     */
    protected function replaceHolidayPlaceholders($string, Value $ddate, $locale)
    {
        if (strlen($this->getHolyday($ddate, $locale)))
        {
            $string = str_replace('%N', '', $string);
            $string = str_replace('%H', $this->getHolyday($ddate, $locale), $string);
            return $string;
        }
        else
        {
            $string = preg_replace('/%N(.)*/s', '', $string);
            $string = str_replace('%H', $this->noHolyday, $string);
            return $string;
        }
    }
}
