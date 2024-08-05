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
 * @package EmperorNortonCommands\lib\Ddate
 * @api
 */
final readonly class DiscordianDate
{
    /**
     * The Curse of Greyface occurred in 1 YOLD and thus defines the offset
     * from the Gregorian calendar, according to which it was 1166 BC.
     */
    private const int CURSE_OF_GREYFACE = 1166;

    /**
     * The sixtieth day of the year (Gregorian Calendar) is either Feb 29 (in
     * leap years) / St. Tibs Day (Discordian Calendar) or Mar 1 / 60th of
     * Chaos (Discordian Calendar).
     */
    private const int SIXTIETH_DAY_OF_THE_YEAR = 60;

    private function __construct(
        public DiscordianDay|StTibsDay $day,
        public DiscordianWeekday|StTibsDay $weekday,
        public DiscordianSeason|StTibsDay $season,
        public DiscordianYear  $year,
        public DateTimeImmutable $gregorian,
    ) {
    }

    public static function fromDateTimeInterface(DateTimeInterface $dateTime): DiscordianDate
    {
        return new DiscordianDate(
            self::getDiscordianDay($dateTime),
            self::getDiscordianWeekday($dateTime),
            self::getDiscordianSeason($dateTime),
            self::getDiscordianYear($dateTime),
            DateTimeImmutable::createFromInterface($dateTime),
        );
    }

    private static function getDiscordianDay(DateTimeInterface $dateTime): DiscordianDay|StTibsDay
    {
        if (self::isStTibsDay($dateTime)) {
            return new StTibsDay();
        }
        $dayOfYearModulo73 = self::getDayOfYear($dateTime) % 73;
        $discordianDayAsInt = $dayOfYearModulo73 === 0 ? 73 : $dayOfYearModulo73;
        return DiscordianDay::from($discordianDayAsInt);
    }

    private static function getDiscordianWeekday(DateTimeInterface $dateTime): DiscordianWeekday|StTibsDay
    {
        if (self::isStTibsDay($dateTime)) {
            return new StTibsDay();
        }
        $dayOfYearModulo5 = self::getDayOfYear($dateTime) % 5;
        $discordianDayOfWeekAsInt = $dayOfYearModulo5 === 0 ? 5 : $dayOfYearModulo5;
        return DiscordianWeekday::from($discordianDayOfWeekAsInt);
    }

    private static function getDiscordianSeason(DateTimeInterface $dateTime): DiscordianSeason|StTibsDay
    {
        if (self::isStTibsDay($dateTime)) {
            return new StTibsDay();
        }
        $dayOfYear = self::getDayOfYear($dateTime);
        return match (true) {
            $dayOfYear <= 73 => DiscordianSeason::Chaos,
            $dayOfYear <= 146 => DiscordianSeason::Discord,
            $dayOfYear <= 219 => DiscordianSeason::Confusion,
            $dayOfYear <= 292 => DiscordianSeason::Bureaucracy,
            $dayOfYear > 292 => DiscordianSeason::The_Aftermath,
        };
    }

    private static function getDiscordianYear(DateTimeInterface $dateTime): DiscordianYear
    {
        return new DiscordianYear((int) $dateTime->format('Y') + self::CURSE_OF_GREYFACE);
    }

    /**
     * Calculates current day of year in days from 1st of Chaos. St. Tibs Day
     * does not count, i.e. it is ignored.
     */
    private static function getDayOfYear(DateTimeInterface $dateTime): int
    {
        $dayOfYearGregorian = self::getDayOfYearGregorian($dateTime);
        return $dayOfYearGregorian < self::SIXTIETH_DAY_OF_THE_YEAR
                ? $dayOfYearGregorian
                : (self::isLeapYear($dateTime) ? $dayOfYearGregorian - 1 : $dayOfYearGregorian);
    }

    private static function isStTibsDay(DateTimeInterface $dateTime): bool
    {
        return (int) $dateTime->format('d') === 29 && (int) $dateTime->format('m') === 2;
    }

    private static function isLeapYear(DateTimeInterface $dateTime): bool
    {
        return (bool) $dateTime->format('L');
    }

    private static function getDayOfYearGregorian(DateTimeInterface $dateTime): int
    {
        return ((int) $dateTime->format('z')) + 1;
    }
}
