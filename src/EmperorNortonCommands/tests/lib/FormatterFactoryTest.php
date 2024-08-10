<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

namespace EmperorNortonCommands\tests\lib;

use EmperorNortonCommands\lib\FormatterFactory;
use EmperorNortonCommands\lib\Internal\Formatter\GermanFormatter as GermanStandardFormatter;
use EmperorNortonCommands\lib\Internal\Formatter\EnglishFormatter as EnglishStandardFormatter;
use EmperorNortonCommands\lib\Locale;
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
