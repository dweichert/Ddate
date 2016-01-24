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
        if ($this->isStTibsDay($date->format('m'), $date->format('d')))
        {
            return $this->calculateDdateStTibs($date);
        }
        return $this->calculateDdate($date);
    }

    /**
     * Conversion algorithm for St. Tibs Day.
     *
     * @param \DateTime $date
     * @return DdateValue
     */
    protected function calculateDdateStTibs(\DateTime $date)
    {
        $ddate = new DdateValue();
        $ddate->setDay(DdateValue::ST_TIBS_DAY);
        $ddate->setSeason(DdateValue::ST_TIBS_DAY);
        $ddate->setWeekDay(DdateValue::ST_TIBS_DAY);
        $ddate->setYear($this->calculateYear($date));
        $ddate->setDaysUntilXDay($this->calculateDaysUntilXday($date));
        return $ddate;
    }

    /**
     * Regular conversion algorithm.
     *
     * @param \DateTime $date
     * @return DdateValue
     */
    protected function calculateDdate(\DateTime $date)
    {
        $ddate = new DdateValue();
        $ddate->setDay($this->calculateDayofSeason($date));
        $ddate->setSeason($this->calculateSeason($date));
        $ddate->setWeekDay($this->calculateDayOfWeek($date));
        $ddate->setYear($this->calculateYear($date));
        $ddate->setDaysUntilXDay($this->calculateDaysUntilXday($date));
        $ddate->setHolyday($this->getHolyday($date));
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
    protected function getHolyday(\DateTime $date)
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
    protected function calculateDayOfWeek(\DateTime $date)
    {
        $dayOfYear = $this->getDaysSinceFirstOfChaos($date);
        $leapYear = $this->isLeapYear($date);
        $dayOfWeekIdx = ($dayOfYear - (1 + $this->getOffset($leapYear, $dayOfYear))) % 5;
        return $dayOfWeekIdx + 1;
    }

    /**
     * Calculate day of Discordian season.
     *
     * @param  \DateTime $date
     * @return integer
     */
    protected function calculateDayofSeason(\DateTime $date)
    {
        $dayOfYear = $this->getDaysSinceFirstOfChaos($date);
        return (($dayOfYear - (1 + $this->getOffset($this->isLeapYear($date), $dayOfYear))) % 73) + 1;
    }

    /**
     * Calculate season of Discordian year.
     *
     * @param  \DateTime $date
     * @return integer
     */
    protected function calculateSeason(\DateTime $date)
    {
        $seasonIdx = 0;
        $dayOfYear = $this->getDaysSinceFirstOfChaos($date);
        if ($dayOfYear > 59)
        {
            $dayOfYearMinusStTibs = $dayOfYear - $this->getOffset($this->isLeapYear($date), $dayOfYear);
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
    protected function calculateYear(\DateTime $date)
    {
        return $this->_yearDiscordian = $date->format('Y') + $this->_curseOfGreyface;
    }

    /**
     * Calculate days until X-Day.
     *
     * @param  \DateTime $date
     * @return integer
     */
    protected function calculateDaysUntilXday(\DateTime $date)
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
    protected function getOffset($leapYear, $dayOfYear)
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
    protected function isStTibsDay($monthGregorian, $dayGregorian)
    {
        return 2 == $monthGregorian && 29 == $dayGregorian;
    }

    /**
     * Get days since 1st of Chaos.
     *
     * @param  \DateTime $date
     * @return integer
     */
    protected function getDaysSinceFirstOfChaos(\DateTime $date)
    {
        $firstOfChaos = gmmktime(0, 0, 0, 1, 1, $date->format('Y'));
        return (integer)($this->getTimestamp($date) - $firstOfChaos) / 86400 + 1;
    }

    /**
     * Get leap year.
     *
     * @param \DateTime $date
     * @return boolean
     */
    protected function isLeapYear(\DateTime $date)
    {
        return (boolean)date('L', $this->getTimestamp($date));
    }

    /**
     * Get Unix time stamp.
     *
     * @param  \DateTime $date
     * @return integer
     */
    protected function getTimestamp(\DateTime $date)
    {
        return gmmktime(0, 0, 0, $date->format('m'), $date->format('d'), $date->format('Y'));
    }
}
