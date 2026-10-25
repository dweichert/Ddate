<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

declare(strict_types=1);

namespace EmperorNortonCommands\lib\Holydays;

use DateTime;
use EmperorNortonCommands\lib\Value;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Class EristerTest.
 *
 * @package EmperorNortonCommands\lib\Holydays
 */
final class EristerTest extends TestCase
{
    #[DataProvider('isEristerProvider')]
    public function testIsErister($expectedTrue, $gregorian, $usePhpCalendarExt): void
    {
        $object = new Erister($usePhpCalendarExt);
        self::assertSame($expectedTrue, $object->is($this->getValue($gregorian)));
    }

    public static function isEristerProvider(): array
    {
        return [
            '27.03.354' =>  [true, '27030354', true],
            '27.03.354 - no extension' =>  [true, '27030354', false],
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

    private function getValue(string $gregorian): Value
    {
        return new Value(0, 0, 0, 0, 0, 0, DateTime::createFromFormat('dmY', $gregorian));
    }
}
