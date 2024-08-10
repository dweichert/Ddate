<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */
declare(strict_types=1);

namespace EmperorNortonCommands\lib\Internal\Formatter;

use EmperorNortonCommands\lib\DiscordianDate;
use EmperorNortonCommands\lib\Internal\Holydays\StandardHolydays;
use EmperorNortonCommands\lib\Internal\Legacy\Holydays;
use EmperorNortonCommands\lib\Internal\Legacy\StTibsDay;
use EmperorNortonCommands\lib\Internal\Legacy\Value;
use EmperorNortonCommands\lib\XDay;

/**
 * @package EmperorNortonCommands\lib\Ddate
 * @internal
 */
abstract class Formatter
{
    /**
     * Returns array of all supported format strings.
     *
     * @var array<string, string>
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
     * @return array<string, string>
     */
    public function getSupportedFormatStringFields(): array
    {
        return $this->supportedFormatStringFields;
    }

    /**
     * Sets format to internal property.
     *
     * If no format string is given default format string is used as fallback.
     *
     * @param string|null $format OPTIONAL will use default format as fallback.
     */
    public function setFormat($format = null)
    {
        if (null === $format || (is_object($format) && !method_exists($format, '__toString'))) {
            $format = $this->defaultFormat;
        }
        $this->format = (string)$format;
    }

    public function format(string|null $format, DiscordianDate $ddate): string
    {
        $value = new Value(
            $ddate->day instanceof StTibsDay ? Value::ST_TIBS_DAY : $ddate->day->value,
            $ddate->season instanceof StTibsDay ? Value::ST_TIBS_DAY : $ddate->season->value,
            $ddate->weekday instanceof StTibsDay ? Value::ST_TIBS_DAY : $ddate->weekday->value,
            $ddate->year->value,
            XDay::daysUntilRealXDay($ddate->gregorian),
            XDay::daysUntilOriginalXDay($ddate->gregorian),
            $ddate->gregorian,
        );
        $this->setFormat($format);

        return $this->formatLegacy($value);
    }

    /**
     * Get formatted Discordian date.
     *
     * @param  Value $ddate
     * @deprecated with v2.0.0
     * @return string
     */
    abstract public function formatLegacy(Value $ddate);

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
