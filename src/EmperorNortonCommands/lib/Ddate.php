<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

namespace EmperorNortonCommands\lib;

/**
 * Class Ddate.
 *
 * @package EmperorNortonCommands
 */
class Ddate
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
        '%X' => 'Number of days remaining until X-Day.',
        '%{' => 'Enclose the part of the string which is to be replaced with "St. Tib\'s Day" if the current day is St. Tib\'s Day (start delimiter)',
        '%}' => 'Enclose the part of the string which is to be replaced with "St. Tib\'s Day" if the current day is St. Tib\'s Day (end delimiter)'
    );

    /**
     * Gregorian day number.
     *
     * @var integer
     */
    protected $_dayGregorian = 0;

    /**
     * Gregorian month number.
     *
     * @var integer
     */
    protected $_monthGregorian = 0;

    /**
     * Gregorian year number.
     *
     * @var integer
     */
    protected $_yearGregorian = 0;

    /**
     * Discordian day number.
     *
     * @var integer
     */
    protected $_dayDiscordian = 0;

    /**
     * Discordian season number.
     *
     * @var integer
     */
    protected $_seasonDiscordian = 0;

    /**
     * Discordian week day.
     *
     * @var integer
     */
    protected $_weekDayDiscordian = 0;

    /**
     * The day of the year.
     *
     * @var integer
     */
    protected $_dayOfYear = 0;

    /**
     * Representation of date as Unix timestamp.
     *
     * @var integer
     */
    protected $_timestamp = 0;

    /**
     * Format string.
     *
     * @var string
     */
    protected $_format;

    /**
     * Locale data.
     *
     * @var LocaleData
     */
    protected $_localeData;

    /**
     * Discordian date converter.
     *
     * @var DdateConverter
     */
    protected $_converter;

    /**
     * Constructor method.
     *
     * @param LocaleData $localeData class providing localized messages
     */
    public function __construct(LocaleData $localeData = null)
    {
        if (is_null($localeData))
        {
            $this->_localeData = new LocaleDataEn();
        }
        $this->_converter = new DdateConverter();
    }

    /**
     * Returns array of all supported format strings.
     *
     * @return string[]
     */
    public function getSupportedFormatStringFields()
    {
        return $this->_supportedFormatStringFields;
    }

    /**
     * Convert Gregorian to Discordian dates.
     *
     * Returns the date in Discordian date format. If called with no arguments,
     * the current system date will be used. Alternatively, a Gregorian date
     * may be specified as the second argument of the function, in form of
     * a day, month and year (dmY).
     *
     * If a format string is specified as the first argument, the Discordian
     * date will be returned in a format specified by the string. This
     * mechanism works similarly to the format string mechanism of date(), only
     * almost completely differently.
     *
     * @param  string                    $format OPTIONAL format string
     * @param  string                    $date   OPTIONAL Gregorian date
     * @return string
     * @throws \InvalidArgumentException
     */
    public function ddate($format = null, $date = null)
    {
        if (!$this->_setDate($date))
        {
            throw new \InvalidArgumentException('Second argument expected to be a Gregorian date (dmY).');
        }
        $this->_setFormat($format);
        return $this->_getFormattedDiscordianDate();
    }

    /**
     * Get cardinal day from ordinal day.
     *
     * Returns "FNORD" on St. Tibs Day.
     *
     * @return string
     */
    protected function _getCardinalDay()
    {
        if ($this->_isStTibsDay())
        {
            return 'FNORD';
        }
        $suffix = 'th';
        if (!in_array($this->_getDiscordianDay(), array(11, 12, 13)))
        {
            $lastDigitDay = substr($this->_getDiscordianDay(), -1, 1);
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
        return $this->_getDiscordianDay() . $suffix;
    }

    /**
     * Set date from input.
     *
     * @param  string $date Gregorian date (dmY)
     * @return boolean
     */
    protected function _setDate($date)
    {
        if (null == $date)
        {
            $dateObj = new \DateTime();
            return $this->_setDateFromObject($dateObj);
        }
        return $this->_setDateFromString($date);
    }

    /**
     * Sets date from string.
     *
     * @param  string $date Gregorian date (dmY)
     * @return boolean
     */
    protected function _setDateFromString($date)
    {
        if (!is_numeric($date) && 8 != strlen($date))
        {
            return false;
        }
        list($year, $month, $day) = $this->_splitIntoParts($date);
        if (!checkdate($month, $day, $year))
        {
            return false;
        }
        $this->_dayGregorian = $day;
        $this->_monthGregorian = $month;
        $this->_yearGregorian = $year;
        $this->_timestamp = gmmktime(0, 0, 0, $this->_monthGregorian, $this->_dayGregorian, $this->_yearGregorian);
        return true;
    }

    /**
     * Splits date string into parts.
     *
     * Returns array($day, $month, $year).
     *
     * @param  string $date Gregorian date (dmY)
     * @return array
     */
    protected function _splitIntoParts($date)
    {
        $year = (integer)substr($date, 4, 4);
        $month = (integer)substr($date, 2, 2);
        $day = (integer)substr($date, 0, 2);
        return array($year, $month, $day);
    }

    /**
     * Sets date from DateTime object.
     *
     * @param  \DateTime $dateObj
     * @return boolean
     */
    protected function _setDateFromObject(\DateTime $dateObj)
    {
        $this->_dayGregorian = (integer)$dateObj->format('d');
        $this->_monthGregorian = (integer)$dateObj->format('m');
        $this->_yearGregorian = (integer)$dateObj->format('Y');
        $this->_timestamp = $dateObj->getTimestamp();
        return true;
    }

    /**
     * Returns true if it is St. Tib's Day.
     *
     * @return boolean
     */
    protected function _isStTibsDay()
    {
        return 2 == $this->_monthGregorian && 29 == $this->_dayGregorian;
    }

    /**
     * Returns true if it is a Holyday.
     *
     * @return boolean
     */
    protected function _isHolyday()
    {
        return array_key_exists($this->_getDayMonth(), $this->_localeData->getHolydays());
    }

    /**
     * Get day and month.
     *
     * Returns padded Gregorian day and Gregorian month, e. g.: 0101 for
     * January 1st, 2902 for February 29th, 0612 for December 6th, ...
     *
     * @return string
     */
    protected function _getDayMonth()
    {
        return str_pad($this->_dayGregorian, 2, '0', STR_PAD_LEFT)
               . str_pad($this->_monthGregorian, 2, '0', STR_PAD_LEFT);
    }

    /**
     * Get days since 1st of January.
     *
     * @return integer
     */
    protected function _getDayOfYear()
    {
        if ($this->_dayOfYear != 0)
        {
            return $this->_dayOfYear;
        }
        $januaryFirst = gmmktime(0, 0, 0, 1, 1, $this->_yearGregorian);
        return $this->_dayOfYear = (integer)($this->_timestamp - $januaryFirst) / 86400 + 1;
    }

    /**
     * Sets format to internal property.
     *
     * If no format string is given default format string is used as fallback.
     *
     * @param string $format
     */
    protected function _setFormat($format)
    {
        if (null == $format || (is_object($format) && !method_exists($format, '__toString')))
        {
            $format = '%{%A, %B %d,%} %Y YOLD';
        }
        $this->_format = (string)$format;
    }

    /**
     * Get Discordian season.
     *
     * Returns "FNORD" on St. Tibs Day.
     *
     * @return integer|string
     */
    protected function _getDiscordianSeason()
    {
        if ($this->_isStTibsDay())
        {
            return 'FNORD';
        }
        if (0 != $this->_seasonDiscordian)
        {
            return $this->_seasonDiscordian;
        }
        $this->_calculateDiscordianDate();
        return $this->_seasonDiscordian;
    }

    /**
     * Get Discordian (ordinal) day.
     *
     * Returns "FNORD" on St. Tibs Day.
     *
     * @return integer|string
     */
    protected function _getDiscordianDay()
    {
        if ($this->_isStTibsDay())
        {
            return 'FNORD';
        }
        if (0 != $this->_dayDiscordian)
        {
            return $this->_dayDiscordian;
        }
        $this->_calculateDiscordianDate();
        return $this->_dayDiscordian;
    }

    /**
     * Get Discordian day of the week.
     *
     * Returns "FNORD" on St. Tibs Day.
     *
     * @return integer|string
     */
    protected function _getDiscordianWeekDay()
    {
        if ($this->_isStTibsDay())
        {
            return 'FNORD';
        }
        if (0 != $this->_weekDayDiscordian)
        {
            return $this->_weekDayDiscordian;
        }
        $this->_calculateDiscordianDate();
        return $this->_weekDayDiscordian;
    }

    /**
     * Get Discrodian season name.
     *
     * @return string
     */
    protected function _getDiscordianSeasonName()
    {
        if ($this->_isStTibsDay())
        {
            return 'FNORD';
        }
        $seasons = $this->_localeData->getSeasons();
        return (string)$seasons[$this->_getDiscordianSeason()];
    }

    /**
     * Get abbreviated Discordian season name.
     *
     * @return string
     */
    protected function _getAbbreviatedSeasonName()
    {
        if ($this->_isStTibsDay())
        {
            return 'FNORD';
        }
        $abbrevSeasons = $this->_localeData->getAbbrevSeasons();
        return $abbrevSeasons[$this->_getDiscordianSeason()];
    }

    /**
     * Get Discordian week day name.
     *
     * @return string
     */
    protected function _getDiscordianWeekDayName()
    {
        if ($this->_isStTibsDay())
        {
            return 'FNORD';
        }
        $days = $this->_localeData->getDays();
        return (string)$days[$this->_getDiscordianWeekDay()];
    }

    /**
     * Get abbreviated Discordian week day name.
     *
     * @return string
     */
    protected function _getAbbreviatedWeekDayName()
    {
        if ($this->_isStTibsDay())
        {
            return 'FNORD';
        }
        $abbrevDays = $this->_localeData->getAbbrevDays();
        return $abbrevDays[$this->_getDiscordianWeekDay()];
    }

    /**
     * Calculate Discordian date.
     */
    protected function _calculateDiscordianDate()
    {
        $leapYear = date('L', $this->_timestamp);
        $this->_weekDayDiscordian = $this->_converter->calculateDayOfWeek($leapYear, $this->_getDayOfYear());
        $this->_dayDiscordian = $this->_converter->calculateDayofSeason($leapYear, $this->_getDayOfYear());
        $this->_seasonDiscordian =  $this->_converter->calculateSeason($leapYear, $this->_getDayOfYear());
    }

    /**
     * Get formatted Discordian date.
     *
     * @return string
     */
    protected function _getFormattedDiscordianDate()
    {
        $output = $this->_format;
        $output = $this->_replaceStTibsPlaceholders($output);
        $output = $this->_replaceHolidayPlaceholders($output);
        $output = str_replace('%a', $this->_getAbbreviatedWeekDayName(), $output);
        $output = str_replace('%A', $this->_getDiscordianWeekDayName(), $output);
        $output = str_replace('%B', $this->_getDiscordianSeasonName(), $output);
        $output = str_replace('%b', $this->_getAbbreviatedSeasonName(), $output);
        $output = str_replace('%e', $this->_getCardinalDay(), $output);
        $output = str_replace('%d', $this->_getDiscordianDay(), $output);
        $output = str_replace('%Y', $this->_converter->calculateYear($this->_yearGregorian), $output);
        $output = $this->_replaceXDayPlaceholder($output);
        $output = str_replace('%t', "\t", $output);
        $output = str_replace('%n', "\n", $output);
        return (string)$output;
    }

    /**
     * Replaces %{ and %} placeholders in given string.
     *
     * @param  string $string
     * @return string
     */
    protected function _replaceStTibsPlaceholders($string)
    {
        if ($this->_isStTibsDay())
        {
            $holidays = $this->_localeData->getHolydays();
            $string = preg_replace('/%{(.)*%}/', $holidays['2902'], $string);
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
     * @return string
     */
    protected function _replaceHolidayPlaceholders($string)
    {
        if ($this->_isHolyday())
        {
            $string = str_replace('%N', '', $string);
            $holidays = $this->_localeData->getHolydays();
            $string = str_replace('%H', $holidays[$this->_getDayMonth()], $string);
            return $string;
        }
        else
        {
            $string = preg_replace('/%N(.)*/s', '', $string);
            $string = str_replace('%H', 'no Holyday', $string);
            return $string;
        }
    }

    /**
     * Replaces %X in given string.
     *
     * @param  string $string
     * @return string
     */
    protected function _replaceXDayPlaceholder($string)
    {
        $date = new \DateTime($this->_yearGregorian . '-' . $this->_monthGregorian . '-' . $this->_dayGregorian);
        return str_replace('%X', $this->_converter->calculateDaysUntilXday($date), $string);
    }
}
