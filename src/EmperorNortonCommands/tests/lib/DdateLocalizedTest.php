<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

namespace EmperorNortonCommands\tests\lib;

use EmperorNortonCommands\lib\Ddate;
use EmperorNortonCommands\lib\GermanStandardFormatter;

/**
 * Class DdateLocalizedTest.
 *
 * @package EmperorNortonCommands\tests\lib
 */
class DdateLocalizedTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Object instance to be tested.
     *
     * @var Ddate
     */
    protected $_object;

    /**
     * Set up.
     */
    public function setUp()
    {
        $this->_object = new Ddate();
    }

    /**
     * Data Provider to test ddate() method.
     *
     * @return array
     */
    public function dataProvider()
    {
        return array(
            array('03051998', 'Pungenday, Discord 50, 3164 YOLD', null, 'en'),
            array('29021996', 'St. Tibs Tag 3162 n. Gre.', null, 'de'),
            array('07021974', 'Stechend, Verwirrung 38, 3140 n. Gre.', null, 'de'),
            array('03012013', 'ST, Verwirrung 3, 3179', '%{%a, %B %d,%} %Y', 'de'),
            array('08122014', 'Heute ist Dröhntag, der Fünfzigster Ausklang 3180 n. Gre.' . "\n\t" . 'Wir feiern Ausfluss.', 'Heute ist %{%A, der %e %B %Y n. Gre.%N%n%tWir feiern %H.', 'de'),
            array('14031999', 'ST, Ve Dreiundsiebzigster 3165', '%a, %b %e %Y', 'de'),
            array('29022012', "Heute ist St. Tibs Tag 3178, noch 2428624 Tage bis zum Tag X", "Heute ist %{%A, the %e of %B,%} %Y, noch %X Tage bis zum Tag X", 'de'),
        );
    }

    /**
     * Test ddate() with locale.
     *
     * @dataProvider dataProvider
     */
    public function testDdateLocalized($gregorian, $discordian, $format, $locale)
    {
        $expected = $discordian;
        $actual = $this->_object->ddate($format, $gregorian, $locale);
        self::assertEquals($expected, $actual);
    }

    /**
     * Test makes sure that output with locale 'en' matches default output.
     */
    public function testLocaleEn()
    {
        self::assertEquals($this->_object->ddate(), $this->_object->ddate(null, null, 'en'));
    }
}