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
     */
    public function getFormatter(Locale $locale): Formatter
    {
        $formatter = (string)$this->availableFormatters[$locale->value]['class'];

        return new $formatter();
    }
}
