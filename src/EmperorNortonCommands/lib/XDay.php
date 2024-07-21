<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */
declare(strict_types=1);

namespace EmperorNortonCommands\lib;

use DateTimeImmutable;
use DateTimeInterface;

/**
 * Provides functions to calculate days until original and real X-Day.
 *
 * @package EmperorNortonCommands\lib\Ddate
 * @api
 */
final readonly class XDay
{
    /**
     * Official X-Day.
     *
     * Since the official X-Day did not occur, it was found that the original
     * calculation got the date upside down, hence 8661 instead of 1998.
     */
    private const REAL_X_DAY = '8661-07-05T11:00:00+0000';

    /**
     * Official X-Day date identified by J. R. "Bob" Dobbs in the 1950s.
     *
     * The event was according to Reverend Ivan Stang to happen at 7am local
     * time (Brushwood Folklore Center, New York) on July 5th, 1998, hence
     * 11:00:00 UTC (taking into account the timezone offset and the daylight
     * saving time in effect on the given date).
     *
     * See: Deborah Scoblionkov: Armageddon Ends Badly, Wired, 06. Jul. 1998,
     * https://www.wired.com/1998/07/armageddon-ends-badly/
     */
    private const ORIGINAL_X_DAY = '1998-07-05T11:00:00+0000';

    public static function daysUntilRealXDay(DateTimeInterface $dateTime): int
    {
        $xDay = DateTimeImmutable::createFromFormat(DateTimeInterface::ATOM, self::REAL_X_DAY);
        return self::dateDiffInDays($dateTime, $xDay);
    }

    public static function daysUntilOriginalXDay(DateTimeInterface $dateTime): int
    {
        $xDay = DateTimeImmutable::createFromFormat(DateTimeInterface::ATOM, self::ORIGINAL_X_DAY);
        return self::dateDiffInDays($dateTime, $xDay);
    }

    /**
     * Calculate days from $from until $until.
     */
    private static function dateDiffInDays(
        DateTimeInterface $from,
        DateTimeInterface $until,
    ): int {
        $diff = $until->diff($from);
        if ($from < $until) {
            return (int) $diff->days;
        }
        return (int) $diff->days * -1;
    }
}
