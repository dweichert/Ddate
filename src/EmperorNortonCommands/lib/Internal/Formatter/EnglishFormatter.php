<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

namespace EmperorNortonCommands\lib\Internal\Formatter;

use EmperorNortonCommands\lib\DiscordianDate;
use EmperorNortonCommands\lib\DiscordianDate\StTibsDay;
use EmperorNortonCommands\lib\FormatterInterface;
use EmperorNortonCommands\lib\Internal\Holydays\CamdenBenaresHolidays;
use EmperorNortonCommands\lib\Internal\Holydays\RevDrJonSwabeyWhollydays;
use EmperorNortonCommands\lib\Internal\Holydays\RevLoveshadeWhollydays;
use EmperorNortonCommands\lib\Internal\Holydays\StandardHolydays;
use EmperorNortonCommands\lib\Internal\Legacy\Value;
use EmperorNortonCommands\lib\Locale;
use EmperorNortonCommands\lib\XDay;

/**
 * @package EmperorNortonCommands\lib\Ddate
 * @internal
 */
class EnglishFormatter extends Formatter implements FormatterInterface
{
    /**
     * Full names of the day of the week.
     *
     * @var string[]
     */
    protected $days = ['Sweetmorn', 'Boomtime', 'Pungenday', 'Prickle-Prickle', 'Setting Orange'];

    /**
     * Abbreviated names of the day of the week.
     *
     * @var string[]
     */
    protected $abbrevDays = ['SM', 'BT', 'PD', 'PP', 'SO'];

    /**
     * Full names of the season.
     *
     * @var string[]
     */
    protected $seasons = ['Chaos', 'Discord', 'Confusion', 'Bureaucracy', 'The Aftermath'];

    /**
     * Abbreviated names of the seasons.
     *
     * @var string[]
     */
    protected $abbrevSeasons = ['Chs', 'Dsc', 'Cfn', 'Bcy', 'Afm'];

    /**
     * No Holyday (msgid) string.
     *
     * @var string
     */
    protected $noHolyday = 'no Holyday';

    public function format(string|null $format, DiscordianDate $ddate): string
    {
        $value = new Value(
            $ddate->day instanceof StTibsDay ? Value::ST_TIBS_DAY : $ddate->day->value,
            $ddate->season instanceof StTibsDay ? Value::ST_TIBS_DAY : $ddate->season->value,
            $ddate->weekday instanceof StTibsDay ? Value::ST_TIBS_DAY : $ddate->weekday->value,
            $ddate->year->value,
            $ddate->gregorian,
        );
        $this->format = $format ?? DefaultFormatString::fromLocale(Locale::English)->value;
        $output = $this->format;
        $output = $this->replaceHolidaySetPlaceholders($output);
        $output = $this->replaceStTibsPlaceholders($output, $value);
        $output = $this->replaceHolidayPlaceholders($output, $value, 'en');
        $output = str_replace('%a', $this->getAbbreviatedWeekDayName($value->getWeekDay()), $output);
        $output = str_replace('%A', $this->getDiscordianWeekDayName($value->getWeekDay()), $output);
        $output = str_replace('%B', $this->getDiscordianSeasonName($value->getSeason()), $output);
        $output = str_replace('%b', $this->getAbbreviatedSeasonName($value->getSeason()), $output);
        $output = str_replace('%e', $this->getCardinalDay($value->getDay()), $output);
        $output = str_replace('%d', $this->getOrdinalDay($value), $output);
        $output = str_replace('%Y', $ddate->year->value, $output);
        $output = str_replace('%X', XDay::daysUntilRealXDay($ddate->gregorian), $output);
        $output = str_replace('%x', XDay::daysUntilOriginalXDay($ddate->gregorian), $output);
        $output = str_replace('%t', "\t", $output);
        $output = str_replace('%n', "\n", $output);
        return (string)$output;
    }

    public function getSupportedFormatStringFields(): array
    {
        return [
            '%A' => 'Full name of the day of the week (e.g. Sweetmorn)',
            '%a' => 'Abbreviated name of the day of the week (e.g. SM)',
            '%B' => 'Full name of the season (e.g. Chaos)',
            '%b' => 'Abbreviated name of the season (e.g. Chs)',
            '%d' => 'Ordinal number of the day in the season (e.g. 23)',
            '%e' => 'Cardinal number of the day in the season (e.g. 23rd)',
            '%Y' => 'A full numeric representation of a year, 4 digits',
            '%H' => 'Name of current Holyday, if any',
            '%N' => 'Magic code to prevent rest of the format being printed unless today is a Holyday',
            '%n' => 'Newline',
            '%t' => 'Tab',
            '%X' => 'Number of days since / until remaining until X-Day.',
            '%x' => 'Number of days since / until original X-Day (Jul 5th, 1998)',
            '%{' => 'Enclose the part of the string which is to be replaced with "St. Tib\'s Day" if the current day is St. Tib\'s Day (start delimiter)',
            '%}' => 'Enclose the part of the string which is to be replaced with "St. Tib\'s Day" if the current day is St. Tib\'s Day (end delimiter)',
            '%1' => 'remove standard Holydays',
            '%2' => 'add "Camden Beneres\' Holidays" to Holydays',
            '%3' => 'add "Reverent DrJon Swabey\'s Whollydays" to Holydays',
            '%4' => 'add "Reverent Loveshade\'s Whollydays" to Holydays',
        ];
    }

    /**
     * Replaces %{ and %} placeholders in given string.
     *
     * @param  string     $string
     * @param  Value $ddate
     * @return string
     */
    protected function replaceStTibsPlaceholders($string, Value $ddate)
    {
        if (Value::ST_TIBS_DAY === $ddate->getDay()) {
            $string = preg_replace('/%{(.)*%}/', "St. Tib's Day", $string);
            return $string;
        } else {
            $string = str_replace('%{', '', $string);
            $string = str_replace('%}', '', $string);
            return $string;
        }
    }

    /**
     * Get abbreviated Discordian week day name.
     *
     * @return string
     */
    protected function getAbbreviatedWeekDayName($weekDay)
    {
        if (Value::ST_TIBS_DAY == $weekDay) {
            return 'FNORD';
        }
        $weekDay = $weekDay - 1;
        return $this->abbrevDays[$weekDay];
    }

    /**
     * Get Discordian week day name.
     *
     * @return string
     */
    protected function getDiscordianWeekDayName($weekDay)
    {
        if (Value::ST_TIBS_DAY == $weekDay) {
            return 'FNORD';
        }
        $weekDay = $weekDay - 1;
        return $this->days[$weekDay];
    }

    /**
     * Get Discrodian season name.
     *
     * @param integer $season Discordian (ordinal) season
     * @return string
     */
    protected function getDiscordianSeasonName($season)
    {
        if (Value::ST_TIBS_DAY == $season) {
            return 'FNORD';
        }
        $season = $season - 1;
        return (string)$this->seasons[$season];
    }

    /**
     * Get abbreviated Discordian season name.
     *
     * @return string
     */
    protected function getAbbreviatedSeasonName($season)
    {
        if (Value::ST_TIBS_DAY == $season) {
            return 'FNORD';
        }
        $season = $season - 1;
        return $this->abbrevSeasons[$season];
    }

    /**
     * Get cardinal day from ordinal day.
     *
     * Returns "FNORD" on St. Tibs Day.
     *
     * @param integer $day Discordian day of (ordinal) season
     * @return string
     */
    protected function getCardinalDay($day)
    {
        if (Value::ST_TIBS_DAY == $day) {
            return 'FNORD';
        }
        $suffix = 'th';
        if (!in_array($day, [11, 12, 13])) {
            $lastDigitDay = substr($day, -1, 1);
            switch ($lastDigitDay) {
                case '1':
                    $suffix = 'st';
                    break;
                case '2':
                    $suffix = 'nd';
                    break;
                case '3':
                    $suffix = 'rd';
                    break;
                default:
                    break;
            }
        }
        return $day . $suffix;
    }

    /**
     * Get ordinal day.
     *
     * Returns "FNORD" on St. Tibs Day.
     *
     * @param Value $ddate
     * @return integer|string
     */
    protected function getOrdinalDay(Value $ddate)
    {
        if (Value::ST_TIBS_DAY == $ddate->getDay()) {
            return 'FNORD';
        }
        return $ddate->getDay();
    }

    protected function replaceHolidaySetPlaceholders($string)
    {
        $turnOffStandardHolydays = 0;
        $turnOnCamdenBenaresHolidays = 0;
        $turnOnRevDrJonSwabeyWhollydays = 0;
        $turnOnReverendLoveshadeWhollydays = 0;

        $string = str_replace('%1', '', $string, $turnOffStandardHolydays);
        $string = str_replace('%2', '', $string, $turnOnCamdenBenaresHolidays);
        $string = str_replace('%3', '', $string, $turnOnRevDrJonSwabeyWhollydays);
        $string = str_replace('%4', '', $string, $turnOnReverendLoveshadeWhollydays);

        $this->toggleHolydaySets(
            $turnOffStandardHolydays,
            $turnOnCamdenBenaresHolidays,
            $turnOnRevDrJonSwabeyWhollydays,
            $turnOnReverendLoveshadeWhollydays
        );

        return $string;
    }

    /**
     * Get localized string with all holydays.
     */
    protected function getHolydayString($holydays)
    {
        if (empty($holydays)) {
            return $this->noHolyday;
        }

        if (1 == count($holydays)) {
            return $holydays[0];
        }

        if (2 == count($holydays)) {
            return $holydays[0] . ' and ' . $holydays[1];
        }

        $lastHolyday = array_pop($holydays);

        return implode(', ', $holydays) . ' and ' . $lastHolyday;
    }

    /**
     * Toggle Holyday Sets.
     *
     * @param integer $turnOffStandardHolydays
     * @param integer $turnOnCamdenBenaresHolidays
     * @param integer $turnOnRevDrJonSwabeyWhollydays
     * @param integer $turnOnReverendLoveshadeWhollydays
     */
    protected function toggleHolydaySets(
        $turnOffStandardHolydays,
        $turnOnCamdenBenaresHolidays,
        $turnOnRevDrJonSwabeyWhollydays,
        $turnOnReverendLoveshadeWhollydays
    ) {
        // because Standard Holydays are a subset of Rev. DrJon Swabey Whollydays
        if ($turnOffStandardHolydays && !$turnOnRevDrJonSwabeyWhollydays) {
            unset($this->holydays[StandardHolydays::getKey()]);
        }
        if ($turnOnCamdenBenaresHolidays) {
            $this->holydays[CamdenBenaresHolidays::getKey()] = new CamdenBenaresHolidays();
        }
        if ($turnOnRevDrJonSwabeyWhollydays) {
            $this->holydays[RevDrJonSwabeyWhollydays::getKey()] = new RevDrJonSwabeyWhollydays();
        }
        if ($turnOnReverendLoveshadeWhollydays) {
            $this->holydays[RevLoveshadeWhollydays::getKey()] = new RevLoveshadeWhollydays();
        }
    }
}
