<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

namespace EmperorNortonCommands\lib;

/**
 * Class FormatterFactory
 * @package EmperorNortonCommands\lib
 */
class FormatterFactory
{
    /**
     * Available formatters.
     *
     * @var mixed[]
     */
    private $availableFormatters = [
        'en' => [
            'lang' => 'English',
            'class' => locale\en\StandardFormatter::class,
            'holydays' => ['Standard' => 'EmperorNortonCommands\lib\locale\en\StandardHolydays']
        ],
        'de' => [
            'lang' => 'Deutsch',
            'class' => locale\de\StandardFormatter::class,
            'holydays' => ['Standard' => 'EmperorNortonCommands\lib\locale\de\StandardHolydays']
        ]
    ];

    /**
     * Get Discordian date formatter.
     *
     * @param  string $locale two-letter locale identifier, e.g. "en" for English
     * @return Formatter
     */
    public function getFormatter($locale)
    {
        if (null === $locale || (is_object($locale) && !method_exists($locale, '__toString')))
        {
            $locale = 'en';
        }
        $locale = strtolower(substr($locale, 0, 2));
        if (!array_key_exists($locale, $this->availableFormatters))
        {
            $locale = 'en';
        }
        $formatter = (string)$this->availableFormatters[$locale]['class'];

        return new $formatter();
    }
}
