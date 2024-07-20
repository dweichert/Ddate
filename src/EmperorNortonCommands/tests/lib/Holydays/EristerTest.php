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
use PHPUnit\Framework\TestCase;

class EristerTest extends TestCase
{
    #[DataProvider('isEristerProvider')] public function testIsErister($expectedTrue, $gregorian, $usePhpCalendarExt)
    {
        $object = new Erister($usePhpCalendarExt);
        if ($expectedTrue) {
            self::assertTrue($object->is($this->getMockValue($gregorian)));
        } else {
            self::assertFalse($object->is($this->getMockValue($gregorian)));
        }
    }

    public static function isEristerProvider()
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

    private function getMockValue($gregorian)
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
