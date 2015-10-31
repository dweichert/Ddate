<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

namespace EmperorNortonCommands\lib;

/**
 * Class DdateConverter.
 *
 * Converts Gregorian dates to Discordian dates.
 * @package EmperorNortonCommands\lib
 */
class DdateConverter
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
     * Convert Gregorian to Discordian Date.
     *
     * @param \DateTime $date
     * @return DdateValue
     */
    public function convert(\DateTime $date)
    {
        if ($this->_isStTibsDay($date->format('m'), $date->format('d')))
        {
            return $this->_calculateDdateStTibs($date);
        }
        return $this->_calculateDdate($date);
    }

    /**
     * Conversion algorithm for St. Tibs Day.
     *
     * @param \DateTime $date
     * @return DdateValue
     */
    protected function _calculateDdateStTibs(\DateTime $date)
    {
        $ddate = new DdateValue();
        $ddate->setDay(DdateValue::ST_TIBS_DAY);
        $ddate->setSeason(DdateValue::ST_TIBS_DAY);
        $ddate->setWeekDay(DdateValue::ST_TIBS_DAY);
        $ddate->setYear($this->_calculateYear($date));
        $ddate->setDaysUntilXDay($this->_calculateDaysUntilXday($date));
        return $ddate;
    }

    /**
     * Regular conversion algorithm.
     *
     * @param \DateTime $date
     * @return DdateValue
     */
    protected function _calculateDdate(\DateTime $date)
    {
        $ddate = new DdateValue();
        $ddate->setDay($this->_calculateDayofSeason($date));
        $ddate->setSeason($this->_calculateSeason($date));
        $ddate->setWeekDay($this->_calculateDayOfWeek($date));
        $ddate->setYear($this->_calculateYear($date));
        $ddate->setDaysUntilXDay($this->_calculateDaysUntilXday($date));
        $ddate->setHolyday($this->_getHolyday($date));
        return $ddate;
    }

    /**
     * Get Holyday.
     *
     * Returns empty string if $date is not a Holyday.
     *
     * @param \DateTime $date
     * @return string
     */
    protected function _getHolyday(\DateTime $date)
    {
        $key = $date->format('d') . $date->format('m');
        if (isset($this->_holydays[$key]))
        {
            return $this->_holydays[$key];
        }
        return '';
    }

    /**
     * Calculate day of Discordian week.
     *
     * @param  \DateTime $date
     * @return integer
     */
    protected function _calculateDayOfWeek(\DateTime $date)
    {
        $dayOfYear = $this->_getDaysSinceFirstOfChaos($date);
        $leapYear = $this->_isLeapYear($date);
        $dayOfWeekIdx = ($dayOfYear - (1 + $this->_getOffset($leapYear, $dayOfYear))) % 5;
        return $dayOfWeekIdx + 1;
    }

    /**
     * Calculate day of Discordian season.
     *
     * @param  \DateTime $date
     * @return integer
     */
    protected function _calculateDayofSeason(\DateTime $date)
    {
        $dayOfYear = $this->_getDaysSinceFirstOfChaos($date);
        return (($dayOfYear - (1 + $this->_getOffset($this->_isLeapYear($date), $dayOfYear))) % 73) + 1;
    }

    /**
     * Calculate season of Discordian year.
     *
     * @param  \DateTime $date
     * @return integer
     */
    protected function _calculateSeason(\DateTime $date)
    {
        $seasonIdx = 0;
        $dayOfYear = $this->_getDaysSinceFirstOfChaos($date);
        if ($dayOfYear > 59)
        {
            $dayOfYearMinusStTibs = $dayOfYear - $this->_getOffset($this->_isLeapYear($date), $dayOfYear);
            for ($i = 0; $i < 5; $i++)
            {
                if ($dayOfYearMinusStTibs < (74 + $i * 73))
                {
                    $seasonIdx = $i;
                    break;
                }
            }
        }
        return $seasonIdx + 1;
    }

    /**
     * Get Discordian year.
     *
     * @param  \DateTime $date
     * @return integer
     */
    protected function _calculateYear(\DateTime $date)
    {
        return $this->_yearDiscordian = $date->format('Y') + $this->_curseOfGreyface;
    }

    /**
     * Calculate days until X-Day.
     *
     * @param  \DateTime $date
     * @return integer
     */
    protected function _calculateDaysUntilXday(\DateTime $date)
    {
        $xDay = new \DateTime('8661-07-05', new \DateTimeZone('UTC'));
        $diff = $xDay->diff($date);
        $daysUntilXday = $diff->days;
        return (integer)$daysUntilXday;
    }

    /**
     * Get offset for leap years.
     *
     * In leap years for days after the 60th day of the year, i.e. 29th of
     * February there is an offset of 1 day to be taken into account.
     *
     * @return integer
     */
    protected function _getOffset($leapYear, $dayOfYear)
    {
        if ($dayOfYear < 60)
        {
            return 0;
        }
        return $leapYear ? 1 : 0;
    }

    /**
     * Returns true if it is St. Tib's Day.
     *
     * @param  integer $monthGregorian
     * @param  integer $dayGregorian
     * @return boolean
     */
    protected function _isStTibsDay($monthGregorian, $dayGregorian)
    {
        return 2 == $monthGregorian && 29 == $dayGregorian;
    }

    /**
     * Get days since 1st of Chaos.
     *
     * @param  \DateTime $date
     * @return integer
     */
    protected function _getDaysSinceFirstOfChaos(\DateTime $date)
    {
        $firstOfChaos = gmmktime(0, 0, 0, 1, 1, $date->format('Y'));
        return (integer)($this->_getTimestamp($date) - $firstOfChaos) / 86400 + 1;
    }

    /**
     * Get leap year.
     *
     * @param \DateTime $date
     * @return boolean
     */
    protected function _isLeapYear(\DateTime $date)
    {
        return (boolean)date('L', $this->_getTimestamp($date));
    }

    /**
     * Get Unix time stamp.
     *
     * @param  \DateTime $date
     * @return integer
     */
    protected function _getTimestamp(\DateTime $date)
    {
        return gmmktime(0, 0, 0, $date->format('m'), $date->format('d'), $date->format('Y'));
    }
}