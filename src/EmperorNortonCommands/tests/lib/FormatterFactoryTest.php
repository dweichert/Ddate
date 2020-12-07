<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

namespace EmperorNortonCommands\tests\lib;

use EmperorNortonCommands\lib\locale\en\StandardFormatter as EnglishStandardFormatter;
use EmperorNortonCommands\lib\FormatterFactory;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Class FormatterFactoryTest.
 *
 * @package EmperorNortonCommands\tests\lib
 */
class FormatterFactoryTest extends TestCase
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
    public function setUp(): void
    {
        $this->_object = new FormatterFactory();
    }

    /**
     * Data provider for getFormatter().
     *
     * @return array
     */
    public function dataProvider()
    {
        return array(
            array('Foo', 'EmperorNortonCommands\lib\locale\en\StandardFormatter'),
            array(new stdClass(), 'EmperorNortonCommands\lib\locale\en\StandardFormatter')
        );
    }

    /**
     * Test getFormatter()
     *
     * @dataProvider dataProvider
     */
    public function testGetFormatter($locale, $expected)
    {
        self::assertTrue($this->_object->getFormatter($locale) instanceof $expected);
    }
}
