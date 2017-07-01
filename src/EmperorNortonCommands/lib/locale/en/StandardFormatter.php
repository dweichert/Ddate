<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

namespace EmperorNortonCommands\lib\locale\en;

use EmperorNortonCommands\lib\Formatter;
use EmperorNortonCommands\lib\Holydays\CamdenBenaresHolidays;
use EmperorNortonCommands\lib\Holydays\RevDrJonSwabeyWhollydays;
use EmperorNortonCommands\lib\Holydays\RevLoveshadeWhollydays;
use EmperorNortonCommands\lib\Holydays\StandardHolydays;
use EmperorNortonCommands\lib\Value;

/**
 * Class StandardFormatter
 * @package EmperorNortonCommands\lib\locale\en
 */
class StandardFormatter extends Formatter
{
    /**
     * Supported format string fields and description.
     *
     * @var string[]
     */
    protected $supportedFormatStringFields = array(
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
    );

    /**
     * Default format.
     *
     * @var string
     */
    protected $defaultFormat = '%{%A, %B %d,%} %Y YOLD';

    /**
     * Full names of the day of the week.
     *
     * @var string[]
     */
    protected $days = array('Sweetmorn', 'Boomtime', 'Pungenday', 'Prickle-Prickle', 'Setting Orange');

    /**
     * Abbreviated names of the day of the week.
     *
     * @var string[]
     */
    protected $abbrevDays = array('SM', 'BT', 'PD', 'PP', 'SO');

    /**
     * Full names of the season.
     *
     * @var string[]
     */
    protected $seasons = array('Chaos', 'Discord', 'Confusion', 'Bureaucracy', 'The Aftermath');

    /**
     * Abbreviated names of the seasons.
     *
     * @var string[]
     */
    protected $abbrevSeasons = array('Chs', 'Dsc', 'Cfn', 'Bcy', 'Afm');

    /**
     * No Holyday (msgid) string.
     *
     * @var string
     */
    protected $noHolyday = 'no Holyday';

    /**
     * Format Value as string.
     *
     * @param  Value $ddate
     * @return string
     */
    public function format(Value $ddate)
    {
        $output = $this->format;
        $output = $this->replaceHolidaySetPlaceholders($output);
        $output = $this->replaceStTibsPlaceholders($output, $ddate);
        $output = $this->replaceHolidayPlaceholders($output, $ddate, 'en');
        $output = str_replace('%a', $this->getAbbreviatedWeekDayName($ddate->getWeekDay()), $output);
        $output = str_replace('%A', $this->getDiscordianWeekDayName($ddate->getWeekDay()), $output);
        $output = str_replace('%B', $this->getDiscordianSeasonName($ddate->getSeason()), $output);
        $output = str_replace('%b', $this->getAbbreviatedSeasonName($ddate->getSeason()), $output);
        $output = str_replace('%e', $this->getCardinalDay($ddate->getDay()), $output);
        $output = str_replace('%d', $this->getOrdinalDay($ddate), $output);
        $output = str_replace('%Y', $ddate->getYear(), $output);
        $output = str_replace('%X', $ddate->getDaysUntilRealXDay(), $output);
        $output = str_replace('%x', $ddate->getDaysUntilOriginalXDay(), $output);
        $output = str_replace('%t', "\t", $output);
        $output = str_replace('%n', "\n", $output);
        return (string)$output;
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
        if (Value::ST_TIBS_DAY === $ddate->getDay())
        {
            $string = preg_replace('/%{(.)*%}/', "St. Tib's Day", $string);
            return $string;
        }
        else
        {
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
        if (Value::ST_TIBS_DAY == $weekDay)
        {
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
        if (Value::ST_TIBS_DAY == $weekDay)
        {
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
        if (Value::ST_TIBS_DAY == $season)
        {
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
        if (Value::ST_TIBS_DAY == $season)
        {
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
        if (Value::ST_TIBS_DAY == $day)
        {
            return 'FNORD';
        }
        $suffix = 'th';
        if (!in_array($day, array(11, 12, 13)))
        {
            $lastDigitDay = substr($day, -1, 1);
            switch ($lastDigitDay)
            {
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
        if (Value::ST_TIBS_DAY == $ddate->getDay())
        {
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

        return explode(', ', $holydays) . ' and ' . $lastHolyday;
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
    )
    {
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
        if ($turnOnReverendLoveshadeWhollydays)
        {
            $this->holydays[RevLoveshadeWhollydays::getKey()] = new RevLoveshadeWhollydays();
        }
    }
}
