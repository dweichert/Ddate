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
        if (null === $format || (is_object($format) && !method_exists($format, '__toString'))) {
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
     * @return string[]
     */
    protected function getHolydays(Value $ddate, $locale)
    {
        $holydays = array();
        foreach ($this->holydays as $holyday) {
            $holydays = array_merge($holydays, $holyday->getHolyday($ddate, $locale));
        }

        return $holydays;
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
        $holydays = $this->getHolydays($ddate, $locale);

        if (empty($holydays)) {
            $string = preg_replace('/%N(.)*/s', '', $string);
        } else {
            $string = str_replace('%N', '', $string);
        }
        $string = str_replace('%H', $this->getHolydayString($holydays), $string);

        return $string;
    }

    /**
     * Get localized string with all holydays.
     */
    abstract protected function getHolydayString($holydays);
}
