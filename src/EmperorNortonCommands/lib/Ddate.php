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
     * Curse of Greyface.
     *
     * The Curse of Greyface occurred in 1 YOLD and thus defines the offset
     * from the Gregorian calendar, according to which it was 1166 BC.
     *
     * @var integer
     */
    protected $_curseOfGreyface = 1166;

    /**
     * Discordian Holydays.
     *
     * @var string[]
     */
    protected $_holydays = array(
        '0501' => 'Mungday',
        '1902' => 'Chaoflux',
        '2902' => 'St. Tib\'s Day',
        '1903' => 'Mojoday',
        '0305' => 'Discoflux',
        '3105' => 'Syaday',
        '1507' => 'Conflux',
        '1208' => 'Zaraday',
        '2609' => 'Bureflux',
        '2410' => 'Maladay',
        '0812' => 'Afflux'
    );

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
     * Discordian year number.
     *
     * @var integer
     */
    protected $_yearDiscordian = 0;

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
     * @param  integer $day
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
        return array_key_exists($this->_getDayMonth(), $this->_holydays);
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
     * Calculate days until X-Day.
     *
     * @return integer
     */
    protected function _getDaysUntilXday()
    {
        $xDay = new \DateTime('8661-07-05');
        $date = new \DateTime($this->_yearGregorian . '-' . $this->_monthGregorian . '-' . $this->_dayGregorian);
        $diff = $xDay->diff($date);
        $daysUntilXday = $diff->days;
        return (integer)$daysUntilXday;
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
     * Get Discordian year.
     *
     * @return integer
     */
    protected function _getDiscordianYear()
    {
        if (0 != $this->_yearDiscordian)
        {
            return $this->_yearDiscordian;
        }
        return $this->_yearDiscordian = $this->_yearGregorian + $this->_curseOfGreyface;
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
        return (string)$this->_seasons[$this->_getDiscordianSeason()];
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
        return $this->_abbrevSeasons[$this->_getDiscordianSeason()];
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
        return (string)$this->_days[$this->_getDiscordianWeekDay()];
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
        return $this->_abbrevDays[$this->_getDiscordianWeekDay()];
    }

    /**
     * Calculate Discordian date.
     */
    protected function _calculateDiscordianDate()
    {
        $this->_weekDayDiscordian = ($this->_getDayOfYear() - (1 + $this->_getOffset())) % 5;
        $this->_seasonDiscordian =  $this->_calculateSeason();
        $this->_dayDiscordian = (($this->_getDayOfYear() - (1 + $this->_getOffset())) % 73) + 1;
    }

    /**
     * Get offset for leap years.
     *
     * In leap years for days after the 60th day of the year, i.e. 29th of
     * February there is an offset of 1 day to be taken into account.
     *
     * @return integer
     */
    protected function _getOffset()
    {
        if ($this->_getDayOfYear() < 60)
        {
            return 0;
        }
        return (integer)date('L', $this->_timestamp);
    }

    /**
     * Calculate Discordian season.
     *
     * @return integer
     */
    protected function _calculateSeason()
    {
        $seasonIdx = 0;
        if ($this->_getDayOfYear() > 59)
        {
            $dayOfYearMinusStTibs = $this->_getDayOfYear() - $this->_getOffset();
            for ($i = 0; $i < 5; $i++)
            {
                if ($dayOfYearMinusStTibs < (74 + $i * 73))
                {
                    $seasonIdx = $i;
                    break;
                }
            }
            return $seasonIdx;
        }
        return $seasonIdx;
    }

    /**
     * Get formatted Discordian date.
     *
     * @return string
     */
    protected function _getFormattedDiscordianDate()
    {
        $output = $this->_format;
        if ($this->_isStTibsDay())
        {
            $output = preg_replace('/%{(.)*%}/', $this->_holydays['2902'], $output);
        }
        else
        {
            $output = str_replace('%{', '', $output);
            $output = str_replace('%}', '', $output);
        }
        if ($this->_isHolyday())
        {
            $output = str_replace('%N', '', $output);
            $output = str_replace('%H', $this->_holydays[$this->_getDayMonth()], $output);
        }
        else
        {
            $output = preg_replace('/%N(.)*/s', '', $output);
            $output = str_replace('%H', 'no Holyday', $output);
        }
        $output = str_replace('%a', $this->_getAbbreviatedWeekDayName(), $output);
        $output = str_replace('%A', $this->_getDiscordianWeekDayName(), $output);
        $output = str_replace('%B', $this->_getDiscordianSeasonName(), $output);
        $output = str_replace('%b', $this->_getAbbreviatedSeasonName(), $output);
        $output = str_replace('%e', $this->_getCardinalDay(), $output);
        $output = str_replace('%d', $this->_getDiscordianDay(), $output);
        $output = str_replace('%Y', $this->_getDiscordianYear(), $output);
        $output = str_replace('%X', $this->_getDaysUntilXday(), $output);
        $output = str_replace('%t', "\t", $output);
        $output = str_replace('%n', "\n", $output);
        return (string)$output;
    }
}
