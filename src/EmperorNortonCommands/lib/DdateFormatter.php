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
     * Get supported format string fields.
     *
     * @return string[]
     */
    public function getSupportedFormatStringFields()
    {
        return $this->_supportedFormatStringFields;
    }
}