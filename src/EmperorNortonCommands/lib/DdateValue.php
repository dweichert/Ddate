<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

namespace EmperorNortonCommands\lib;


class DdateValue
{
    /**
     * St. Tibs Day Constant: 23 * 23 = 529
     */
    const ST_TIBS_DAY = 529;
    
    /**
     * Discordian day of week.
     *
     * @var integer
     */
    protected $_weekDay;

    /**
     * Discordian day of season.
     *
     * @var integer
     */
    protected $_day;

    /**
     * Discordian season of year.
     *
     * @var integer
     */
    protected $_season;

    /**
     * Discordian year.
     *
     * @var integer
     */
    protected $_year;

    /**
     * Days until real X-Day.
     *
     * @var integer
     */
    protected $_daysUntilRealXDay;

    /**
     * Days until original X-Day.
     *
     * @var integer
     */
    protected $_daysUntilOriginalXDay;

    /**
     * Name of Holyday if any.
     *
     * @var string
     */
    protected $_holyday;
    
    /**
     * Get day of week.
     *
     * @return integer
     */
    public function getWeekDay()
    {
        return $this->_weekDay;
    }

    /**
     * Set day of week.
     *
     * @param integer $weekDay
     * @return DdateValue
     */
    public function setWeekDay($weekDay)
    {
        $this->_weekDay = $weekDay;
        return $this;
    }

    /**
     * Get day of season.
     *
     * @return integer
     */
    public function getDay()
    {
        return $this->_day;
    }

    /**
     * Set day of season.
     *
     * @param integer $day
     * @return DdateValue
     */
    public function setDay($day)
    {
        $this->_day = $day;
        return $this;
    }

    /**
     * Get season.
     *
     * @return integer
     */
    public function getSeason()
    {
        return $this->_season;
    }

    /**
     * Set season.
     *
     * @param integer $season
     * @return DdateValue
     */
    public function setSeason($season)
    {
        $this->_season = $season;
        return $this;
    }

    /**
     * Get year.
     *
     * @return integer
     */
    public function getYear()
    {
        return $this->_year;
    }

    /**
     * Set year.
     *
     * @param integer $year
     * @return DdateValue
     */
    public function setYear($year)
    {
        $this->_year = $year;
        return $this;
    }

    /**
     * Get days until real X-Day.
     *
     * @return integer
     */
    public function getDaysUntilRealXDay()
    {
        return $this->_daysUntilRealXDay;
    }

    /**
     * Set days until real X-Day.
     *
     * @param integer $daysTilXDay
     * @return DdateValue
     */
    public function setDaysUntilRealXDay($daysTilXDay)
    {
        $this->_daysUntilRealXDay = $daysTilXDay;
        return $this;
    }

    /**
     * Get days until original X-Day.
     *
     * @return integer
     */
    public function getDaysUntilOriginalXDay()
    {
        return $this->_daysUntilOriginalXDay;
    }

    /**
     * Set days until original X-Day.
     *
     * @param integer $daysTilXDay
     * @return DdateValue
     */
    public function setDaysUntilOriginalXDays($daysTilXDay)
    {
        $this->_daysUntilOriginalXDay = $daysTilXDay;
        return $this;
    }

    /**
     * Get Holyday.
     *
     * @return string
     */
    public function getHolyday()
    {
        return $this->_holyday;
    }

    /**
     * Set Holyday.
     *
     * @param $holyday
     * @return DdateValue
     */
    public function setHolyday($holyday)
    {
        $this->_holyday = $holyday;
        return $this;
    }
}
