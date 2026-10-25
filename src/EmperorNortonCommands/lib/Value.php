<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

declare(strict_types=1);

namespace EmperorNortonCommands\lib;

use DateTimeInterface;

/**
 * Class Value
 * @package EmperorNortonCommands\lib
 */
class Value
{
    /**
     * St. Tibs Day Constant: 23 * 23 = 529
     */
    public const ST_TIBS_DAY = 529;

    /**
     * Value constructor.
     *
     * The parameters are deliberately not type-declared (part of the API).
     *
     * @param integer           $day                   Discordian day of season
     * @param integer           $season                Discordian season of year
     * @param integer           $weekDay               Discordian day of week
     * @param integer           $year                  Discordian year
     * @param integer           $daysUntilRealXDay     days until real X-Day
     * @param integer           $daysUntilOriginalXDay days until original X-Day
     * @param DateTimeInterface $date                  Gregorian date
     */
    public function __construct(
        private $day,
        private $season,
        private $weekDay,
        private $year,
        private $daysUntilRealXDay,
        private $daysUntilOriginalXDay,
        private $date
    )
    {
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
     * Get days until real X-Day.
     *
     * @return integer
     */
    public function getDaysUntilRealXDay()
    {
        return $this->daysUntilRealXDay;
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
     * Get Gregorian.
     *
     * @return DateTimeInterface
     */
    public function getGregorian()
    {
        return $this->date;
    }
}
