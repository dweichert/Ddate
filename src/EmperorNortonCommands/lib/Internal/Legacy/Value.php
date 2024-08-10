<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

namespace EmperorNortonCommands\lib\Internal\Legacy;

use DateTimeInterface;

/**
 * @package EmperorNortonCommands\lib\Ddate
 * @internal
 */
class Value
{
    /**
     * St. Tibs Day Constant: 23 * 23 = 529
     */
    public const int ST_TIBS_DAY = 529;

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
     * Gregorian date.
     *
     * @var DateTimeInterface
     */
    private $date;

    /**
     * Value constructor.
     * @param integer $day
     * @param integer $season
     * @param integer $weekDay
     * @param integer $year
     * @param DateTimeInterface $date
     */
    public function __construct(
        $day,
        $season,
        $weekDay,
        $year,
        $date
    ) {
        $this->day = $day;
        $this->season = $season;
        $this->weekDay = $weekDay;
        $this->year = $year;
        $this->date = $date;
    }

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
     * Get day of season.
     *
     * @return integer
     */
    public function getDay()
    {
        return $this->day;
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
     * Get year.
     *
     * @return integer
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Get Gregorian.
     *
     * @return DateTimeInterface
     */
    public function getGregorian()
    {
        return $this->date;
    }
}
