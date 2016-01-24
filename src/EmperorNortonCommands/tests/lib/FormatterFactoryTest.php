<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

namespace EmperorNortonCommands\tests\lib;

use EmperorNortonCommands\lib\EnglishStandardFormatter;
use EmperorNortonCommands\lib\FormatterFactory;

/**
 * Class FormatterFactoryTest.
 *
 * @package EmperorNortonCommands\tests\lib
 */
class FormatterFactoryTest extends \PHPUnit_Framework_TestCase
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
    public function setUp()
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
            array('Foo', 'EmperorNortonCommands\lib\EnglishStandardFormatter'),
            array(new \stdClass(), 'EmperorNortonCommands\lib\EnglishStandardFormatter')
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
