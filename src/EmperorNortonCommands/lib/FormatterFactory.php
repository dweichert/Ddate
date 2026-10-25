<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

declare(strict_types=1);

namespace EmperorNortonCommands\lib;

use Stringable;

/**
 * Class FormatterFactory
 * @package EmperorNortonCommands\lib
 */
class FormatterFactory
{
    /**
     * Available formatters by two-letter locale identifier.
     *
     * @var array<string, class-string<Formatter>>
     */
    private const FORMATTERS = [
        'en' => locale\en\StandardFormatter::class,
        'de' => locale\de\StandardFormatter::class,
    ];

    /**
     * Get Discordian date formatter.
     *
     * @param  string $locale two-letter locale identifier, e.g. "en" for English
     * @return Formatter
     */
    public function getFormatter($locale)
    {
        if (null === $locale || (is_object($locale) && !$locale instanceof Stringable))
        {
            $locale = 'en';
        }
        // converted to string the way 1.1 (without strict types) did
        if (is_scalar($locale) || $locale instanceof Stringable)
        {
            $locale = (string)$locale;
        }
        $locale = strtolower(substr($locale, 0, 2));
        $formatter = self::FORMATTERS[$locale] ?? self::FORMATTERS['en'];

        return new $formatter();
    }
}
