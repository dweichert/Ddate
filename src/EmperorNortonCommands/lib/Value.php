<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

namespace EmperorNortonCommands\lib;

/**
 * Class Value
 * @package EmperorNortonCommands\lib
 */
class Value
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
    private $weekDay;

    /**
     * Discordian day of season.
     *
     * @var integer
     */
    private $day;

    /**
     * Discordian season of year.
     *
     * @var integer
     */
    private $season;

    /**
     * Discordian year.
     *
     * @var integer
     */
    private $year;

    /**
     * Days until real X-Day.
     *
     * @var integer
     */
    private $daysUntilRealXDay;

    /**
     * Days until original X-Day.
     *
     * @var integer
     */
    private $daysUntilOriginalXDay;

    /**
     * Name of Holyday if any.
     *
     * @var string
     */
    private $holydayKey;
    
    /**
     * Get day of week.
     *
     * @return integer
     */
    public function getWeekDay()
    {
        return $this->weekDay;
    }

    /**
     * Set day of week.
     *
     * @param integer $weekDay
     * @return Value
     */
    public function setWeekDay($weekDay)
    {
        $this->weekDay = $weekDay;
        return $this;
    }

    /**
     * Get day of season.
     *
     * @return integer
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * Set day of season.
     *
     * @param integer $day
     * @return Value
     */
    public function setDay($day)
    {
        $this->day = $day;
        return $this;
    }

    /**
     * Get season.
     *
     * @return integer
     */
    public function getSeason()
    {
        return $this->season;
    }

    /**
     * Set season.
     *
     * @param integer $season
     * @return Value
     */
    public function setSeason($season)
    {
        $this->season = $season;
        return $this;
    }

    /**
     * Get year.
     *
     * @return integer
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set year.
     *
     * @param integer $year
     * @return Value
     */
    public function setYear($year)
    {
        $this->year = $year;
        return $this;
    }

    /**
     * Get days until real X-Day.
     *
     * @return integer
     */
    public function getDaysUntilRealXDay()
    {
        return $this->daysUntilRealXDay;
    }

    /**
     * Set days until real X-Day.
     *
     * @param integer $daysTilXDay
     * @return Value
     */
    public function setDaysUntilRealXDay($daysTilXDay)
    {
        $this->daysUntilRealXDay = $daysTilXDay;
        return $this;
    }

    /**
     * Get days until original X-Day.
     *
     * @return integer
     */
    public function getDaysUntilOriginalXDay()
    {
        return $this->daysUntilOriginalXDay;
    }

    /**
     * Set days until original X-Day.
     *
     * @param integer $daysTilXDay
     * @return Value
     */
    public function setDaysUntilOriginalXDays($daysTilXDay)
    {
        $this->daysUntilOriginalXDay = $daysTilXDay;
        return $this;
    }

    /**
     * Get Holyday (key).
     *
     * @return string
     */
    public function getHolydayKey()
    {
        return $this->holydayKey;
    }

    /**
     * Set Holyday (key).
     *
     * @param $holyday
     * @return Value
     */
    public function setHolydayKey($holyday)
    {
        $this->holydayKey = $holyday;
        return $this;
    }
}
