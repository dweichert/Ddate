<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

declare(strict_types=1);

namespace EmperorNortonCommands\tests\lib;

use EmperorNortonCommands\lib\locale\de\StandardFormatter as GermanStandardFormatter;
use EmperorNortonCommands\lib\locale\en\StandardFormatter as EnglishStandardFormatter;
use EmperorNortonCommands\lib\FormatterFactory;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Class FormatterFactoryTest.
 *
 * @package EmperorNortonCommands\tests\lib
 */
final class FormatterFactoryTest extends TestCase
{
    /**
     * Object instance to be tested.
     *
     * @var FormatterFactory
     */
    protected $_object;

    /**
     * Set up.
     */
    #[Before]
    public function createObject(): void
    {
        $this->_object = new FormatterFactory();
    }

    /**
     * Data provider for getFormatter().
     *
     * @return array
     */
    public static function dataProvider(): array
    {
        return [
            ['Foo', EnglishStandardFormatter::class],
            [new stdClass(), EnglishStandardFormatter::class],
            [null, EnglishStandardFormatter::class],
            ['de', GermanStandardFormatter::class]
        ];
    }

    /**
     * Test getFormatter()
     */
    #[DataProvider('dataProvider')]
    public function testGetFormatter($locale, $expected): void
    {
        self::assertInstanceOf($expected, $this->_object->getFormatter($locale));
    }
}
