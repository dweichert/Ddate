<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

namespace EmperorNortonCommands\lib\locale\en;

use EmperorNortonCommands\lib\DdateFormatter;
use EmperorNortonCommands\lib\DdateValue;

/**
 * Class EnglishStandardFormatter.
 * @package EmperorNortonCommands\lib
 */
class StandardFormatter extends DdateFormatter
{
    /**
     * Supported format string fields and description.
     *
     * @var string[]
     */
    protected $_supportedFormatStringFields = array(
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
        '%}' => 'Enclose the part of the string which is to be replaced with "St. Tib\'s Day" if the current day is St. Tib\'s Day (end delimiter)'
    );

    /**
     * Default format.
     *
     * @var string
     */
    protected $_defaultFormat = '%{%A, %B %d,%} %Y YOLD';

    /**
     * Full names of the day of the week.
     *
     * @var string[]
     */
    protected $_days = array('Sweetmorn', 'Boomtime', 'Pungenday', 'Prickle-Prickle', 'Setting Orange');

    /**
     * Abbreviated names of the day of the week.
     *
     * @var string[]
     */
    protected $_abbrevDays = array('SM', 'BT', 'PD', 'PP', 'SO');

    /**
     * Full names of the season.
     *
     * @var string[]
     */
    protected $_seasons = array('Chaos', 'Discord', 'Confusion', 'Bureaucracy', 'The Aftermath');

    /**
     * Abbreviated names of the seasons.
     *
     * @var string[]
     */
    protected $_abbrevSeasons = array('Chs', 'Dsc', 'Cfn', 'Bcy', 'Afm');

    /**
     * No Holyday (msgid) string.
     *
     * @var string
     */
    protected $_noHolyday = 'no Holyday';

    /**
     * Format DdateValue as string.
     *
     * @param  DdateValue $ddate
     * @return string
     */
    public function format(DdateValue $ddate)
    {
        $output = $this->_format;
        $output = $this->replaceStTibsPlaceholders($output, $ddate);
        $output = $this->replaceHolidayPlaceholders($output, $ddate);
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
     * @param  DdateValue $ddate
     * @return string
     */
    protected function replaceStTibsPlaceholders($string, DdateValue $ddate)
    {
        if (DdateValue::ST_TIBS_DAY === $ddate->getDay())
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
     * Replaces %N and %H in given string.
     *
     * @param  string $string
     * @param  DdateValue $ddate
     * @return string
     */
    protected function replaceHolidayPlaceholders($string, DdateValue $ddate)
    {
        if (strlen($ddate->getHolyday()))
        {
            $string = str_replace('%N', '', $string);
            $string = str_replace('%H', $this->getHolyday($ddate), $string);
            return $string;
        }
        else
        {
            $string = preg_replace('/%N(.)*/s', '', $string);
            $string = str_replace('%H', $this->_noHolyday, $string);
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
        if (DdateValue::ST_TIBS_DAY == $weekDay)
        {
            return 'FNORD';
        }
        $weekDay = $weekDay - 1;
        return $this->_abbrevDays[$weekDay];
    }

    /**
     * Get Discordian week day name.
     *
     * @return string
     */
    protected function getDiscordianWeekDayName($weekDay)
    {
        if (DdateValue::ST_TIBS_DAY == $weekDay)
        {
            return 'FNORD';
        }
        $weekDay = $weekDay - 1;
        return $this->_days[$weekDay];
    }

    /**
     * Get Discrodian season name.
     *
     * @param integer $season Discordian (ordinal) season
     * @return string
     */
    protected function getDiscordianSeasonName($season)
    {
        if (DdateValue::ST_TIBS_DAY == $season)
        {
            return 'FNORD';
        }
        $season = $season - 1;
        return (string)$this->_seasons[$season];
    }

    /**
     * Get abbreviated Discordian season name.
     *
     * @return string
     */
    protected function getAbbreviatedSeasonName($season)
    {
        if (DdateValue::ST_TIBS_DAY == $season)
        {
            return 'FNORD';
        }
        $season = $season - 1;
        return $this->_abbrevSeasons[$season];
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
        if (DdateValue::ST_TIBS_DAY == $day)
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
     * @param DdateValue $ddate
     * @return integer|string
     */
    protected function getOrdinalDay(DdateValue $ddate)
    {
        if (DdateValue::ST_TIBS_DAY == $ddate->getDay())
        {
            return 'FNORD';
        }
        return $ddate->getDay();
    }

    /**
     * Get Holyday value.
     *
     * @param DdateValue $ddate
     * @return string
     */
    protected function getHolyday(DdateValue $ddate)
    {
        return $ddate->getHolyday();
    }
}
