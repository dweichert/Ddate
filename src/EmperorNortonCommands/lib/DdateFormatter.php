<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

namespace EmperorNortonCommands\lib;

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
    protected $_supportedFormatStringFields = array();

    /**
     * Format string.
     *
     * @var string
     */
    protected $_format = '';

    /**
     * Default format.
     *
     * @var string
     */
    protected $_defaultFormat = '';


    public function __construct()
    {
        $this->setFormat();
    }

    /**
     * Get supported format string fields.
     *
     * @return string[]
     */
    public function getSupportedFormatStringFields()
    {
        return $this->_supportedFormatStringFields;
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
            $format = $this->_defaultFormat;
        }
        $this->_format = (string)$format;
    }

    /**
     * Get formatted Discordian date.
     *
     * @param  DdateValue $ddate
     * @return string
     */
    abstract public function format(DdateValue $ddate);
}
