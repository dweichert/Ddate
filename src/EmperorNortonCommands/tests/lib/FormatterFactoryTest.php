<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

namespace EmperorNortonCommands\tests\lib;

use EmperorNortonCommands\lib\FormatterFactory;
use EmperorNortonCommands\lib\Locale;
use EmperorNortonCommands\lib\locale\de\StandardFormatter as GermanStandardFormatter;
use EmperorNortonCommands\lib\locale\en\StandardFormatter as EnglishStandardFormatter;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class FormatterFactoryTest extends TestCase
{
    /**
     * @param class-string $expected
     */
    #[DataProvider('provideGetFormatter')]
    public function testGetFormatter(
        string $expected,
        Locale $locale,
    ): void {
        self::assertTrue(FormatterFactory::createFormatter($locale) instanceof $expected);
    }

    public static function provideGetFormatter(): array
    {
        return [
            'German Locale' => [GermanStandardFormatter::class, Locale::German],
            'English Locale' => [EnglishStandardFormatter::class, Locale::English],
        ];
    }
}
