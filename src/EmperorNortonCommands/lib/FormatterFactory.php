<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

namespace EmperorNortonCommands\lib;

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
            'class' => 'EnglishStandardFormatter'
        ),
        'de' => array(
            'lang' => 'Deutsch',
            'class' => 'GermanStandardFormatter'
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
            return $this->_availableFormatters['en'];
        }
        $namespace = 'EmperorNortonCommands\\lib\\';
        $class = (string)$namespace . $this->_availableFormatters[$locale]['class'];
        return new $class();
    }
}