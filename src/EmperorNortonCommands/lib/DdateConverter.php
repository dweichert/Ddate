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
     * Discordian year number.
     *
     * @var integer
     */
    protected $_yearDiscordian = 0;

    /**
     * Calculate day of Discordian week.
     *
     * @param  boolean $leapYear  set true for leap years
     * @param  integer $dayOfYear number of days since year began
     * @return integer
     */
    public function calculateDayOfWeek($leapYear, $dayOfYear)
    {
        return ($dayOfYear - (1 + $this->_getOffset($leapYear, $dayOfYear))) % 5;
    }

    /**
     * Calculate day of Discordian season.
     *
     * @param  boolean $leapYear  set true for leap years
     * @param  integer $dayOfYear number of days since year began
     * @return integer
     */
    public function calculateDayofSeason($leapYear, $dayOfYear)
    {
        return (($dayOfYear - (1 + $this->_getOffset($leapYear, $dayOfYear))) % 73) + 1;
    }

    /**
     * Calculate season of Discordian year.
     *
     * @param  boolean $leapYear  set true for leap years
     * @param  integer $dayOfYear number of days since year began
     * @return integer
     */
    public function calculateSeason($leapYear, $dayOfYear)
    {
        $seasonIdx = 0;
        if ($dayOfYear > 59)
        {
            $dayOfYearMinusStTibs = $dayOfYear - $this->_getOffset($leapYear, $dayOfYear);
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
     * Get Discordian year.
     *
     * @param  integer $gregorianYear
     * @return integer
     */
    public function calculateYear($gregorianYear)
    {
        if (0 != $this->_yearDiscordian)
        {
            return $this->_yearDiscordian;
        }
        return $this->_yearDiscordian = $gregorianYear + $this->_curseOfGreyface;
    }

    /**
     * Calculate days until X-Day.
     *
     * @param  \DateTime $date
     * @return integer
     */
    public function calculateDaysUntilXday(\DateTime $date)
    {
        $xDay = new \DateTime('8661-07-05');
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

}