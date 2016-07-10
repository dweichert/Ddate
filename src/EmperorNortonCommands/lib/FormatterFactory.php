<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

namespace EmperorNortonCommands\lib;

use EmperorNortonCommands\lib\locale\en\StandardFormatter as EnglishStandardFormatter;
use EmperorNortonCommands\lib\locale\de\StandardFormatter as GermanStandardFormatter;

/**
 * Class FormatterFactory.
 * @package EmperorNortonCommands\lib
 */
class FormatterFactory
{
    /**
     * Available formatters.
     *
     * @var mixed[]
     */
    protected $_availableFormatters = array(
        'en' => array(
            'lang' => 'English',
            'class' => 'EmperorNortonCommands\lib\locale\en\StandardFormatter'
        ),
        'de' => array(
            'lang' => 'Deutsch',
            'class' => 'EmperorNortonCommands\lib\locale\de\StandardFormatter'
        )
    );

    /**
     * Get Discordian date formatter.
     *
     * @param  string $locale two-letter locale identifier, e.g. "en" for English
     * @return DdateFormatter
     */
    public function getFormatter($locale)
    {
        if (is_object($locale) && !method_exists($locale, '__toString'))
        {
            $locale = 'en';
        }
        $locale = strtolower(substr($locale, 0, 2));
        if (!array_key_exists($locale, $this->_availableFormatters))
        {
            $locale = 'en';
        }
        $class = (string)$this->_availableFormatters[$locale]['class'];
        return new $class();
    }
}
