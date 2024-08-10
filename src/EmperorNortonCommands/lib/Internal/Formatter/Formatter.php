<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */
declare(strict_types=1);

namespace EmperorNortonCommands\lib\Internal\Formatter;

use EmperorNortonCommands\lib\Internal\Holydays\StandardHolydays;
use EmperorNortonCommands\lib\Internal\Legacy\Holydays;
use EmperorNortonCommands\lib\Internal\Legacy\Value;

/**
 * @package EmperorNortonCommands\lib\Ddate
 * @internal
 */
abstract class Formatter
{
    /**
     * Format string.
     *
     * @var string
     */
    protected $format = '';

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
        $this->holydays[StandardHolydays::getKey()] = new StandardHolydays();
    }

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
