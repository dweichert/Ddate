<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

namespace EmperorNortonCommands\lib;

use DateTime;

/**
 * Class Converter.
 *
 * Converts Gregorian dates to Discordian dates.
 * @package EmperorNortonCommands\lib
 */
class Converter
{
    /**
     * Official X-Day.
     *
     * Since the official X-Day did not occur, it was found that the original
     * calculation got the date upside down, hence 8661 instead of 1998.
     */
    const REAL_X_DAY = '8661-07-05T11:00:00+0000';

    /**
     * Official X-Day date identified by J. R. "Bob" Dobbs in the 1950s.
     *
     * See: Deborah Scoblionkov: Armageddon Ends Badly, Wired, 06. Jul. 1998,
     * http://archive.wired.com/culture/lifestyle/news/1998/07/13466
     */
    const ORIGINAL_X_DAY = '1998-07-05T11:00:00+0000';

    /**
     * Curse of Greyface.
     *
     * The Curse of Greyface occurred in 1 YOLD and thus defines the offset
     * from the Gregorian calendar, according to which it was 1166 BC.
     */
    const CURSE_OF_GREYFACE = 1166;

    /**
     * Convert Gregorian to Discordian Date.
     *
     * @param DateTime $date
     * @return Value
     */
    public function convert(DateTime $date)
    {
        if ($this->isStTibsDay($date->format('m'), $date->format('d'))) {
            return $this->calculateDdateStTibs($date);
        }
        return $this->calculateDdate($date);
    }

    /**
     * Conversion algorithm for St. Tibs Day.
     *
     * @param DateTime $date
     * @return Value
     */
    private function calculateDdateStTibs(DateTime $date)
    {
        $ddate = new Value(
            Value::ST_TIBS_DAY,
            Value::ST_TIBS_DAY,
            Value::ST_TIBS_DAY,
            $this->calculateYear($date),
            $this->calculateDaysUntilXday($date),
            $this->calculateDaysUntilOriginalXday($date),
            $date
        );
        return $ddate;
    }

    /**
     * Regular conversion algorithm.
     *
     * @param DateTime $date
     * @return Value
     */
    private function calculateDdate(DateTime $date)
    {
        $ddate = new Value(
            $this->calculateDayofSeason($date),
            $this->calculateSeason($date),
            $this->calculateDayOfWeek($date),
            $this->calculateYear($date),
            $this->calculateDaysUntilXday($date),
            $this->calculateDaysUntilOriginalXday($date),
            $date
        );

        return $ddate;
    }

    /**
     * Calculate day of Discordian week.
     *
     * @param  DateTime $date
     * @return integer
     */
    private function calculateDayOfWeek(DateTime $date)
    {
        $dayOfYear = $this->getDaysSinceFirstOfChaos($date);
        $leapYear = $this->isLeapYear($date);
        $dayOfWeekIdx = ($dayOfYear - (1 + $this->getOffset($leapYear, $dayOfYear))) % 5;
        return $dayOfWeekIdx + 1;
    }

    /**
     * Calculate day of Discordian season.
     *
     * @param  DateTime $date
     * @return integer
     */
    private function calculateDayofSeason(DateTime $date)
    {
        $dayOfYear = $this->getDaysSinceFirstOfChaos($date);
        return (($dayOfYear - (1 + $this->getOffset($this->isLeapYear($date), $dayOfYear))) % 73) + 1;
    }

    /**
     * Calculate season of Discordian year.
     *
     * @param  DateTime $date
     * @return integer
     */
    private function calculateSeason(DateTime $date)
    {
        $seasonIdx = 0;
        $dayOfYear = $this->getDaysSinceFirstOfChaos($date);
        if ($dayOfYear > 59) {
            $dayOfYearMinusStTibs = $dayOfYear - $this->getOffset($this->isLeapYear($date), $dayOfYear);
            for ($i = 0; $i < 5; $i++) {
                if ($dayOfYearMinusStTibs < (74 + $i * 73)) {
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
     * @param  DateTime $date
     * @return integer
     */
    private function calculateYear(DateTime $date)
    {
        return $this->_yearDiscordian = (integer)$date->format('Y') + self::CURSE_OF_GREYFACE;
    }

    /**
     * Calculate days until real X-Day.
     *
     * @param  DateTime $date
     * @return integer
     */
    private function calculateDaysUntilXday(DateTime $date)
    {
        return $this->dateDiffInDays($date, self::REAL_X_DAY);
    }

    /**
     * Calculate days until original X-Day.
     *
     * @param DateTime $date
     * @return integer
     */
    private function calculateDaysUntilOriginalXday(DateTime $date)
    {
        return $this->dateDiffInDays($date, self::ORIGINAL_X_DAY);
    }

    /**
     * Calculate days until date given as ISO 8601 date.
     *
     * @param DateTime $date
     * @param string $iso8601Date
     * @return integer
     */
    private function dateDiffInDays(DateTime $date, $iso8601Date)
    {
        $xDay = new DateTime($iso8601Date);
        $diff = $xDay->diff($date);
        $daysUntilXday = $diff->days;
        if ($date < $xDay) {
            return (integer)$daysUntilXday;
        }
        return (integer)$daysUntilXday * -1;
    }

    /**
     * Get offset for leap years.
     *
     * In leap years for days after the 60th day of the year, i.e. 29th of
     * February there is an offset of 1 day to be taken into account.
     *
     * @return integer
     */
    private function getOffset($leapYear, $dayOfYear)
    {
        if ($dayOfYear < 60) {
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
    private function isStTibsDay($monthGregorian, $dayGregorian)
    {
        return 2 == $monthGregorian && 29 == $dayGregorian;
    }

    /**
     * Get days since 1st of Chaos.
     *
     * @param  DateTime $date
     * @return integer
     */
    private function getDaysSinceFirstOfChaos(DateTime $date)
    {
        $firstOfChaos = gmmktime(0, 0, 0, 1, 1, $date->format('Y'));
        return (integer)($this->getTimestamp($date) - $firstOfChaos) / 86400 + 1;
    }

    /**
     * Get leap year.
     *
     * @param DateTime $date
     * @return boolean
     */
    private function isLeapYear(DateTime $date)
    {
        return (boolean)date('L', $this->getTimestamp($date));
    }

    /**
     * Get Unix time stamp.
     *
     * @param  DateTime $date
     * @return integer
     */
    private function getTimestamp(DateTime $date)
    {
        return gmmktime(0, 0, 0, $date->format('m'), $date->format('d'), $date->format('Y'));
    }
}
