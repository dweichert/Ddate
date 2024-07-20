<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

namespace EmperorNortonCommands\tests\lib\Holydays;

use DateTime;
use EmperorNortonCommands\lib\Holydays\Erister;
use EmperorNortonCommands\lib\Value;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class EristerTest extends TestCase
{
    #[DataProvider('isEristerProvider')]
    public function testIsErister(
        bool $expected,
        string $gregorian,
        bool $usePhpCalendarExt
    ): void {
        $sut = new Erister($usePhpCalendarExt);
        match ($expected) {
            true => self::assertTrue($sut->is($this->getMockValue($gregorian))),
            false => self::assertFalse($sut->is($this->getMockValue($gregorian))),
        };
    }

    public static function isEristerProvider(): array
    {
        return [
            '27.03.354' => [true, '27030354', true],
            '27.03.354 - no extension' => [true, '27030354', false],
            '31.03.1700' => [true, '31031700', true],
            '31.03.1700 - no extension' => [true, '31031700', false],
            '20.01.2000' => [false, '20012000', true],
            '31.03.2002' => [true, '31032002', true],
            '31.03.2002 - no extension' => [true, '31032002', false],
            '31.03.2003' => [false, '31032003', true],
            '31.03.2003 - no extension' => [false, '31032003', false],
            '23.03.9000' => [true, '23039000', true],
            '23.03.9000 - no extension' => [true, '23039000', false],
        ];
    }

    private function getMockValue($gregorian): MockObject&Value
    {
        $mock = $this->getMockBuilder(Value::class)
            ->onlyMethods(['getGregorian'])
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects(self::any())
            ->method('getGregorian')
            ->willReturn(DateTime::createFromFormat('dmY', $gregorian));

        return $mock;
    }
}
