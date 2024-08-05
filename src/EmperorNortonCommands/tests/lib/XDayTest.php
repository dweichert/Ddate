<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

namespace EmperorNortonCommands\tests\lib;

use DateTimeImmutable;
use DateTimeInterface;
use EmperorNortonCommands\lib\XDay;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class XDayTest extends TestCase
{
    #[DataProvider('daysUntilXDayProvider')]
    public function testDaysUntilXDay(
        int $expectedDaysUntilOriginalXDay,
        int $expectedDaysUntilRealXDay,
        DateTimeInterface $dateTime,
    ): void {
        self::assertSame($expectedDaysUntilOriginalXDay, XDay::daysUntilOriginalXDay($dateTime));
        self::assertSame($expectedDaysUntilRealXDay, XDay::daysUntilRealXDay($dateTime));
    }

    public static function daysUntilXDayProvider(): array
    {
        return [
            '25 hours before original X-Day' => [
                'expectedDaysUntilOriginalXDay' => 1,
                'expectedDaysUntilRealXDay' => 2433612,
                'dateTime' => DateTimeImmutable::createFromFormat(
                    format: DateTimeInterface::ATOM,
                    datetime: '1998-07-04T10:00:00+0000'
                ),
            ],
            '24 hours before original X-Day' => [
                'expectedDaysUntilOriginalXDay' => 1,
                'expectedDaysUntilRealXDay' => 2433612,
                'dateTime' => DateTimeImmutable::createFromFormat(
                    format: DateTimeInterface::ATOM,
                    datetime: '1998-07-04T11:00:00+0000'
                ),
            ],
            '23 hours before original X-Day' => [
                'expectedDaysUntilOriginalXDay' => 0,
                'expectedDaysUntilRealXDay' => 2433611,
                'dateTime' => DateTimeImmutable::createFromFormat(
                    format: DateTimeInterface::ATOM,
                    datetime: '1998-07-04T12:00:00+0000'
                ),
            ],
            'Exactly original X-Day' => [
                'expectedDaysUntilOriginalXDay' => 0,
                'expectedDaysUntilRealXDay' => 2433611,
                'dateTime' => DateTimeImmutable::createFromFormat(
                    format: DateTimeInterface::ATOM,
                    datetime: '1998-07-05T11:00:00+0000'
                ),
            ],
            '23 hours after original X-Day' => [
                'expectedDaysUntilOriginalXDay' => 0,
                'expectedDaysUntilRealXDay' => 2433610,
                'dateTime' => DateTimeImmutable::createFromFormat(
                    format: DateTimeInterface::ATOM,
                    datetime: '1998-07-06T10:00:00+0000'
                ),
            ],
            '24 hours after original X-Day' => [
                'expectedDaysUntilOriginalXDay' => -1,
                'expectedDaysUntilRealXDay' => 2433610,
                'dateTime' => DateTimeImmutable::createFromFormat(
                    format: DateTimeInterface::ATOM,
                    datetime: '1998-07-06T11:00:00+0000'
                ),
            ],
            '25 hours after original X-Day' => [
                'expectedDaysUntilOriginalXDay' => -1,
                'expectedDaysUntilRealXDay' => 2433609,
                'dateTime' => DateTimeImmutable::createFromFormat(
                    format: DateTimeInterface::ATOM,
                    datetime: '1998-07-06T12:00:00+0000'
                ),
            ],
            '25 hours before real X-Day' => [
                'expectedDaysUntilOriginalXDay' => -2433609,
                'expectedDaysUntilRealXDay' => 1,
                'dateTime' => DateTimeImmutable::createFromFormat(
                    format: DateTimeInterface::ATOM,
                    datetime: '8661-07-04T10:00:00+0000'
                ),
            ],
            '24 hours before real X-Day' => [
                'expectedDaysUntilOriginalXDay' => -2433610,
                'expectedDaysUntilRealXDay' => 1,
                'dateTime' => DateTimeImmutable::createFromFormat(
                    format: DateTimeInterface::ATOM,
                    datetime: '8661-07-04T11:00:00+0000'
                ),
            ],
            '23 hours before real X-Day' => [
                'expectedDaysUntilOriginalXDay' => -2433610,
                'expectedDaysUntilRealXDay' => 0,
                'dateTime' => DateTimeImmutable::createFromFormat(
                    format: DateTimeInterface::ATOM,
                    datetime: '8661-07-04T12:00:00+0000'
                ),
            ],
            'Exactly real X-Day' => [
                'expectedDaysUntilOriginalXDay' => -2433611,
                'expectedDaysUntilRealXDay' => 0,
                'dateTime' => DateTimeImmutable::createFromFormat(
                    format: DateTimeInterface::ATOM,
                    datetime: '8661-07-05T11:00:00+0000'
                ),
            ],
            '23 hours after real X-Day' => [
                'expectedDaysUntilOriginalXDay' => -2433611,
                'expectedDaysUntilRealXDay' => 0,
                'dateTime' => DateTimeImmutable::createFromFormat(
                    format: DateTimeInterface::ATOM,
                    datetime: '8661-07-06T10:00:00+0000'
                ),
            ],
            '24 hours after real X-Day' => [
                'expectedDaysUntilOriginalXDay' => -2433612,
                'expectedDaysUntilRealXDay' => -1,
                'dateTime' => DateTimeImmutable::createFromFormat(
                    format: DateTimeInterface::ATOM,
                    datetime: '8661-07-06T11:00:00+0000'
                ),
            ],
            '25 hours after real X-Day' => [
                'expectedDaysUntilOriginalXDay' => -2433612,
                'expectedDaysUntilRealXDay' => -1,
                'dateTime' => DateTimeImmutable::createFromFormat(
                    format: DateTimeInterface::ATOM,
                    datetime: '8661-07-06T12:00:00+0000'
                ),
            ],
        ];
    }

}
