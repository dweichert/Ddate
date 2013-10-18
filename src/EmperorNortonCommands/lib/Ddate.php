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
     * Offset of Discordian year compared to Gregorian year.
     *
     * The Curse of Greyface occurred in 1 YOLD and thus defines the offset
     * from the Gregorian calendar, according to which it was 1166 BC.
     *
     * @var integer
     */
    protected $_offsetDiscordianGregorianYear = 1166;

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
    protected $_ddateOutput;

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
        // Validate and sanitize input and set internal date and format
        if (null == $date)
        {
            $date = new \DateTime();
            $this->_setInternalDate($date->format('dmY'));
        }
        else
        {
            if (!$this->_setInternalDate($date))
            {
                throw new \InvalidArgumentException('Second argument expected to be a Gregorian date (dmY).');
            }
        }
        if (null == $format || (is_object($format) && !method_exists($format, '__toString')))
        {
            $format = '%{%A, %B %d,%} %Y YOLD';
        }
        $this->_ddateOutput = (string)$format;

        // Calculate date parts
        $year = $this->_yearGregorian + $this->_offsetDiscordianGregorianYear;
        if ($this->_isStTibsDay())
        {
            $abbrevWkDay = 'FNORD';
            $wkDay = 'FNORD';
            $season = 'FNORD';
            $abbrevSeason = 'FNORD';
            $ordinalDay = 'FNORD';
            $cardinalDay = 'FNORD';
        }
        else
        {
            $leapYearOffset = date('L', $this->_timestamp);
            if ($this->_getDayOfYear() < 60)
            {
                $leapYearOffset = 0;
                $seasonIdx = 0;
            }
            else
            {
                $dayOfYearMinusStTibs = $this->_getDayOfYear() - $leapYearOffset;
                for ($i = 0; $i < 5; $i++)
                {
                    if ($dayOfYearMinusStTibs < (74 + $i * 73))
                    {
                        $seasonIdx = $i;
                        break;
                    }
                }
            }
            $wkDayIdx = ($this->_getDayOfYear() - (1 + $leapYearOffset)) % 5;
            $abbrevWkDay = $this->_abbrevDays[$wkDayIdx];
            $wkDay = $this->_days[$wkDayIdx];
            $season = $this->_seasons[$seasonIdx];
            $abbrevSeason = $this->_abbrevSeasons[$seasonIdx];
            $ordinalDay = (($this->_getDayOfYear() - (1 + $leapYearOffset)) % 73) + 1;
            $cardinalDay = $this->_getCardinalDay($ordinalDay);
        }
        $daysUntilXday = $this->_getDaysUntilXday();

        // Replace fields in format string
        if ($this->_isStTibsDay())
        {
            $this->_ddateOutput = preg_replace('/%{(.)*%}/', $this->_holydays['2902'], $this->_ddateOutput);
        }
        else
        {
            $this->_ddateOutput = str_replace('%{', '', $this->_ddateOutput);
            $this->_ddateOutput = str_replace('%}', '', $this->_ddateOutput);
        }
        if ($this->_isHolyday())
        {
            $this->_ddateOutput = str_replace('%N', '', $this->_ddateOutput);
            $this->_ddateOutput = str_replace('%H', $this->_holydays[$this->_getDayMonth()], $this->_ddateOutput);
        }
        else
        {
            $this->_ddateOutput = preg_replace('/%N(.)*/s', '', $this->_ddateOutput);
            $this->_ddateOutput = str_replace('%H', 'no Holyday', $this->_ddateOutput);
        }
        $this->_ddateOutput = str_replace('%a', $abbrevWkDay, $this->_ddateOutput);
        $this->_ddateOutput = str_replace('%A', $wkDay, $this->_ddateOutput);
        $this->_ddateOutput = str_replace('%B', $season, $this->_ddateOutput);
        $this->_ddateOutput = str_replace('%b', $abbrevSeason, $this->_ddateOutput);
        $this->_ddateOutput = str_replace('%e', $cardinalDay, $this->_ddateOutput);
        $this->_ddateOutput = str_replace('%d', $ordinalDay, $this->_ddateOutput);
        $this->_ddateOutput = str_replace('%Y', $year, $this->_ddateOutput);
        $this->_ddateOutput = str_replace('%X', $daysUntilXday, $this->_ddateOutput);
        $this->_ddateOutput = str_replace('%t', "\t", $this->_ddateOutput);
        $this->_ddateOutput = str_replace('%n', "\n", $this->_ddateOutput);
        return $this->_ddateOutput;
    }

    /**
     * Get cardinal day from ordinal day.
     *
     * @param  integer $day
     * @return string
     */
    protected function _getCardinalDay($day)
    {
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
     * Set date from input.
     *
     * @param  integer $date Gregorian date (dmY)
     * @return boolean
     */
    protected function _setInternalDate($date)
    {
        if (!is_numeric($date) && 8 != strlen($date))
        {
            return false;
        }
        else
        {
            $year = (integer)substr($date, 4, 4);
            $month = (integer)substr($date, 2, 2);
            $day = (integer)substr($date, 0, 2);
        }
        if (!checkdate($month, $day, $year))
        {
            return false;
        }
        $this->_dayGregorian = $day;
        $this->_monthGregorian = $month;
        $this->_yearGregorian = $year;
        $this->_timestamp = gmmktime(
            0, 0, 0, $this->_monthGregorian, $this->_dayGregorian, $this->_yearGregorian
        );
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
     * Get day of year.
     *
     * @return integer
     */
    protected function _getDayOfYear()
    {
        $januaryFirst = gmmktime(0, 0, 0, 1, 1, $this->_yearGregorian);
        return (integer)($this->_timestamp - $januaryFirst) / 86400 + 1;
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
}
