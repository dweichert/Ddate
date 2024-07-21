<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

namespace EmperorNortonCommands\lib;

use EmperorNortonCommands\lib\locale\de\StandardFormatter as GermanStandardFormatter;
use EmperorNortonCommands\lib\locale\en\StandardFormatter as EnglishStandardFormatter;

/**
 * @package EmperorNortonCommands\lib\Ddate
 * @internal
 */
class FormatterFactory
{
    /**
     * Available formatters.
     *
     * @var mixed[]
     */
    private $availableFormatters = array(
        'en' => array(
            'lang' => 'English',
            'class' => EnglishStandardFormatter::class,
            'holydays' => array('Standard' => 'EmperorNortonCommands\lib\locale\en\StandardHolydays')
        ),
        'de' => array(
            'lang' => 'Deutsch',
            'class' => GermanStandardFormatter::class,
            'holydays' => array('Standard' => 'EmperorNortonCommands\lib\locale\de\StandardHolydays')
        )
    );

    /**
     * Get Discordian date formatter.
     *
     * @param  string $locale two-letter locale identifier, e.g. "en" for English
     * @return Formatter
     */
    public function getFormatter($locale)
    {
        if (is_object($locale) && !method_exists($locale, '__toString')) {
            $locale = 'en';
        }
        $locale = strtolower(substr($locale, 0, 2));
        if (!array_key_exists($locale, $this->availableFormatters)) {
            $locale = 'en';
        }
        $formatter = (string)$this->availableFormatters[$locale]['class'];

        return new $formatter();
    }
}
