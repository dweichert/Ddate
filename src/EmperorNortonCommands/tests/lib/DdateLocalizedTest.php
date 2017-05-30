<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

namespace EmperorNortonCommands\tests\lib;

use EmperorNortonCommands\lib\Ddate;
use EmperorNortonCommands\lib\locale\en\StandardHolydays as EnglishStandardHolydays;
use EmperorNortonCommands\lib\locale\de\StandardHolydays as GermanStandardHolydays;
use EmperorNortonCommands\lib\locale\en\StandardFormatter as EnglishStandardFormatter;
use EmperorNortonCommands\lib\locale\de\StandardFormatter as GermanStandardFormatter;
use PHPUnit_Framework_TestCase;

/**
 * Class DdateLocalizedTest.
 *
 * @package EmperorNortonCommands\tests\lib
 */
class DdateLocalizedTest extends PHPUnit_Framework_TestCase
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
            array('07021974', 'Stichtag, Verwirrung 38, 3140 n. Gre.', null, 'de'),
            array('03012013', 'ST, Verwirrung 3, 3179', '%{%a, %B %d,%} %Y', 'de'),
            array('08122014', 'Heute ist Blütezeit, der Fünfzigster Grummet 3180 n. Gre.' . "\n\t" . 'Wir feiern Ausfluss.', 'Heute ist %{%A, der %E %B %Y n. Gre.%N%n%tWir feiern %H.', 'de'),
            array('14031999', 'ST, Ve Dreiundsiebzigster 3165', '%a, %b %E %Y', 'de'),
            array('29022012', "Heute ist St. Tibs Tag 3178, noch 2428624 Tage bis zum Tag X", "Heute ist %{%A, the %E of %B,%} %Y, noch %X Tage bis zum Tag X", 'de'),
            array('28022016', 'Heute ist Prickel-Prickel, Neunundfünfzigster d. Verwirrung im JUHD 3182.', 'Heute ist %{%A, %E d. %B%} im JUHD %Y.%N Wir feiern %H.', 'de'),
            array('28022016', 'Heute ist Prickel-Prickel, Neunundfünfzigster der Verwirrung im JUHD 3182.', 'Heute ist %{%A, %E %C%} im JUHD %Y.%N Wir feiern %H.', 'de'),
            array('05122015', 'Heute ist Prickel-Prickel, Siebenundvierzigster des Grummets im JUHD 3181.', 'Heute ist %{%A, %E %C%} im JUHD %Y.%N Wir feiern %H.', 'de'),
            array('08122015', 'Heute ist Blütezeit, Fünfzigster des Grummets im JUHD 3181. Wir feiern Ausfluss.', 'Heute ist %{%A, %E %C%} im JUHD %Y.%N Wir feiern %H.', 'de'),
            array('05122015', 'Heute ist Prickel-Prickel, 47. des Grummets im JUHD 3181.', 'Heute ist %{%A, %e %C%} im JUHD %Y.%N Wir feiern %H.', 'de'),
            array('11112015', 'Heute ist Orangewerdend, 23. des Grummets im JUHD 3181.', 'Heute ist %{%A, %e %C%} im JUHD %Y.%N Wir feiern %H.', 'de'),
            array('26092015', 'Heute ist Prickel-Prickel, 50. der Beamtenherrschaft im JUHD 3181. Wir feiern Beamtenfluss.', 'Heute ist %{%A, %e %C%} im JUHD %Y.%N Wir feiern %H.', 'de'),
            array('20102015', 'Heute ist Stichtag, 1. des Grummets im JUHD 3181.', 'Heute ist %{%A, %e %C%} im JUHD %Y.%N Wir feiern %H.', 'de'),
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

    /**
     * Test getSupportedFormatFields('de').
     */
    public function testGetSupportedFormatFieldsLocaleDe()
    {
        $formatter = new GermanStandardFormatter(new GermanStandardHolydays());
        $expected = $formatter->getSupportedFormatStringFields();
        $actual = $this->_object->getSupportedFormatStringFields('de');
        self::assertEquals($expected, $actual);
    }

    /**
     * Test getSupportedFormatFields('zz').
     *
     * Unsupported locale, should default to English standard formatter.
     */
    public function testGetSupportedFormatFieldsLocaleZz()
    {
        $formatter = new EnglishStandardFormatter(new EnglishStandardHolydays());
        $expected = $formatter->getSupportedFormatStringFields();
        $actual = $this->_object->getSupportedFormatStringFields('zz');
        self::assertEquals($expected, $actual);
    }

}
