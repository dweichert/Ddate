<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

namespace EmperorNortonCommands\tests\lib;

use EmperorNortonCommands\lib\FormatterFactory;
use EmperorNortonCommands\lib\locale\de\StandardFormatter as GermanStandardFormatter;
use EmperorNortonCommands\lib\locale\en\StandardFormatter as EnglishStandardFormatter;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use stdClass;

final class FormatterFactoryTest extends TestCase
{
    #[DataProvider('dataProvider')]
    public function testGetFormatter(
        string $expected,
        object|string $locale,
    ): void {
        self::assertTrue((new FormatterFactory())->getFormatter($locale) instanceof $expected);
    }

    public static function dataProvider(): array
    {
        return [
            [GermanStandardFormatter::class, 'de'],
            [EnglishStandardFormatter::class, 'Foo'],
            [EnglishStandardFormatter::class, new stdClass()],
        ];
    }
}
