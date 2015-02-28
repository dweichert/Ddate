<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

namespace EmperorNortonCommands\tests\lib;

use EmperorNortonCommands\lib\Ddate;

/**
 * Class DdateTest.
 *
 * @package EmperorNortonCommands
 */
class DdateTest extends \PHPUnit_Framework_TestCase
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
    public function ddateDataProvider()
    {
        return array(
            array('03051998', 'Pungenday, Discord 50, 3164 YOLD', null),
            array('29021996', 'St. Tib\'s Day 3162 YOLD', null),
            array('07021974', 'Pungenday, Chaos 38, 3140 YOLD', null),
            array('31011973', 'Sweetmorn, Chaos 31, 3139 YOLD', null),
            array('16022008', 'Boomtime, Chaos 47, 3174 YOLD', null),
            array('24011948', 'Prickle-Prickle, Chaos 24, 3114 YOLD', null),
            array('25091944', 'Pungenday, Bureaucracy 49, 3110 YOLD', null),
            array('04091920', 'Boomtime, Bureaucracy 28, 3086 YOLD', null),
            array('20091928', 'Pungenday, Bureaucracy 44, 3094 YOLD', null),
            array('30011901', 'Setting Orange, Chaos 30, 3067 YOLD', null),
            array('29022012', 'St. Tib\'s Day 3178 YOLD', null),
            array('01032012', 'Setting Orange, Chaos 60, 3178 YOLD', null),
            array('17091859', 'Setting Orange, Bureaucracy 41, 3025 YOLD', null),
            array('12122012', 'Sweetmorn, The Aftermath 54, 3178 YOLD', null),
            array('07072007', 'Pungenday, Confusion 42, 3173 YOLD', null),
            array('29022012', "Today's St. Tib's Day of FNORD, 3178 YOLD", "Today's %{%A, the %e%} of %B, %Y YOLD"),
            array('29022012', "Today's St. Tib's Day FNORD (FNORD), the FNORD (FNORD) of FNORD (FNORD), 3178 YOLD", "Today's %{%} %A (%a), the %e (%d) of %B (%b), %Y YOLD"),
            array('01012013', 'SM, Chaos 1, 3179', '%{%a, %B %d,%} %Y'),
            array('02012013', 'BT, Chaos 2, 3179', '%{%a, %B %d,%} %Y'),
            array('03012013', 'PD, Chaos 3, 3179', '%{%a, %B %d,%} %Y'),
            array('04012013', 'PP, Chaos 4, 3179', '%{%a, %B %d,%} %Y'),
            array('05012013', 'SO, Chaos 5, 3179', '%{%a, %B %d,%} %Y'),
            array('05012014', 'Today is Setting Orange, the 5th of Chaos, 3180 YOLD' . "\n\t" . 'We celebrate Mungday.', 'Today is %{%A, the %e of %B, %Y YOLD%N%n%tWe celebrate %H.'),
            array('06012014', 'Today is Sweetmorn, the 6th of Chaos, 3180 YOLD', 'Today is %{%A, the %e of %B, %Y YOLD%N%n%tWe celebrate %H.'),
            array('19022014', 'Today is Setting Orange, the 50th of Chaos, 3180 YOLD' . "\n\t" . 'We celebrate Chaoflux.', 'Today is %{%A, the %e of %B, %Y YOLD%N%n%tWe celebrate %H.'),
            array('19032014', 'Today is Pungenday, the 5th of Discord, 3180 YOLD' . "\n\t" . 'We celebrate Mojoday.', 'Today is %{%A, the %e of %B, %Y YOLD%N%n%tWe celebrate %H.'),
            array('03052014', 'Today is Pungenday, the 50th of Discord, 3180 YOLD' . "\n\t" . 'We celebrate Discoflux.', 'Today is %{%A, the %e of %B, %Y YOLD%N%n%tWe celebrate %H.'),
            array('31052014', 'Today is Sweetmorn, the 5th of Confusion, 3180 YOLD' . "\n\t" . 'We celebrate Syaday.', 'Today is %{%A, the %e of %B, %Y YOLD%N%n%tWe celebrate %H.'),
            array('15072014', 'Today is Sweetmorn, the 50th of Confusion, 3180 YOLD' . "\n\t" . 'We celebrate Conflux.', 'Today is %{%A, the %e of %B, %Y YOLD%N%n%tWe celebrate %H.'),
            array('12082014', 'Today is Prickle-Prickle, the 5th of Bureaucracy, 3180 YOLD' . "\n\t" . 'We celebrate Zaraday.', 'Today is %{%A, the %e of %B, %Y YOLD%N%n%tWe celebrate %H.'),
            array('26092014', 'Today is Prickle-Prickle, the 50th of Bureaucracy, 3180 YOLD' . "\n\t" . 'We celebrate Bureflux.', 'Today is %{%A, the %e of %B, %Y YOLD%N%n%tWe celebrate %H.'),
            array('24102014', 'Today is Boomtime, the 5th of The Aftermath, 3180 YOLD' . "\n\t" . 'We celebrate Maladay.', 'Today is %{%A, the %e of %B, %Y YOLD%N%n%tWe celebrate %H.'),
            array('08122014', 'Today is Boomtime, the 50th of The Aftermath, 3180 YOLD' . "\n\t" . 'We celebrate Afflux.', 'Today is %{%A, the %e of %B, %Y YOLD%N%n%tWe celebrate %H.'),
            array('19101999', 'Boomtime, Bureaucracy 73, 3165 YOLD', null),
            array('13032000', 'Boomtime, Chaos 72, 3166 YOLD', null),
            array('14032000', 'Pungenday, Chaos 73, 3166 YOLD', null),
            array('15032000', 'Prickle-Prickle, Discord 1, 3166 YOLD', null),
            array('30121999', 'Prickle-Prickle, The Aftermath 72, 3165 YOLD', new \stdClass()),
            array('31121999', 'Setting Orange, The Aftermath 73, 3165 YOLD', new \stdClass()),
            array('01012000', '', new \SimpleXMLElement('<xml/>')),
            array('14031999', 'PD, Chs 73rd 3165', '%a, %b %e %Y'),
            array('15031999', 'PP, Dsc 1st 3165', '%a, %b %e %Y'),
            array('26051999', 'SM, Dsc 73rd 3165', '%a, %b %e %Y'),
            array('27051999', 'BT, Cfn 1st 3165', '%a, %b %e %Y'),
            array('07081999', 'PP, Cfn 73rd 3165', '%a, %b %e %Y'),
            array('08081999', 'SO, Bcy 1st 3165', '%a, %b %e %Y'),
            array('19101999', 'BT, Bcy 73rd 3165', '%a, %b %e %Y'),
            array('20101999', 'PD, Afm 1st 3165', '%a, %b %e %Y'),
            array('31011999', 'SM, Chs 31st 3165', '%a, %b %e %Y'),
            array('01012000', 'SM, Chs 1st 3166', '%a, %b %e %Y'),
            array('02012000', 'BT, Chs 2nd 3166', '%a, %b %e %Y'),
            array('03012000', 'PD, Chs 3rd 3166', '%a, %b %e %Y'),
            array('04012000', 'PP, Chs 4th 3166', '%a, %b %e %Y'),
            array('05012000', 'SO, Chs 5th 3166', '%a, %b %e %Y'),
            array('10012000', 'SO, Chs 10th 3166', '%a, %b %e %Y'),
            array('11012000', 'SM, Chs 11th 3166', '%a, %b %e %Y'),
            array('12012000', 'BT, Chs 12th 3166', '%a, %b %e %Y'),
            array('13012000', 'PD, Chs 13th 3166', '%a, %b %e %Y'),
            array('14012000', 'PP, Chs 14th 3166', '%a, %b %e %Y'),
            array('14032000', 'PD, Chs 73rd 3166', '%a, %b %e %Y'),
            array('15032000', 'PP, Dsc 1st 3166', '%a, %b %e %Y'),
            array('26052000', 'SM, Dsc 73rd 3166', '%a, %b %e %Y'),
            array('27052000', 'BT, Cfn 1st 3166', '%a, %b %e %Y'),
            array('07082000', 'PP, Cfn 73rd 3166', '%a, %b %e %Y'),
            array('08082000', 'SO, Bcy 1st 3166', '%a, %b %e %Y'),
            array('19102000', 'BT, Bcy 73rd 3166', '%a, %b %e %Y'),
            array('20102000', 'PD, Afm 1st 3166', '%a, %b %e %Y'),
            array('31012000', 'SM, Chs 31st 3166', '%a, %b %e %Y'),
            array('01012001', 'SM, Chs 1st 3167', '%a, %b %e %Y'),
            array('29022012', "Today's St. Tib's Day 3178 YOLD 2428624 days 'til X-Day", "Today's %{%A, the %e of %B,%} %Y YOLD %X days 'til X-Day"),
            array(18092013, 'Today is Sweetmorn, the 42nd of Bureaucracy, 3179.', 'Today is %{%A, the %e of %B%}, %Y.%N %nCelebrate %H'),
            array(26092013, "It's Prickle-Prickle, the 50th of Bureaucracy, 3179. \nCelebrate Bureflux", "It's %{%A, the %e of %B%}, %Y. %N%nCelebrate %H"),
            array(29022016, "Today's St. Tib's Day, 3182. \nCelebrate St. Tib's Day", "Today's %{%A, the %e of %B%}, %Y. %N%nCelebrate %H")
        );
    }

    /**
     * Test ddate().
     *
     * @dataProvider ddateDataProvider
     */
    public function testDdate($gregorian, $discordian, $format)
    {
        $expected = $discordian;
        $actual = $this->_object->ddate($format, $gregorian);
        self::assertEquals($expected, $actual);
    }

    /**
     * Test ddate() with invalid argument (wrong type).
     */
    public function testInvalidDateWrongType()
    {
        $this->setExpectedException(
            'InvalidArgumentException', 'Second argument expected to be a Gregorian date (dmY).'
        );
        $this->_object->ddate(null, 'Lorem ipsum dolor sit amet.');
    }

    /**
     * Test ddate() with invalid argument (not a valid Gregorian date).
     */
    public function testInvalidDate()
    {
        $this->setExpectedException(
            'InvalidArgumentException', 'Second argument expected to be a Gregorian date (dmY).'
        );
        $this->_object->ddate(null, 29021997);
    }

    /**
     * Test to see if build fails as expected.
     */
    public function testTravisFails()
    {
        self::assertEquals('will fail', $this->_object->ddate());
    }

    /**
     * Test ddate with no arguments.
     *
     * Test default behaviour uses expected format and is the same as if
     * today's day month year were given.
     */
    public function testDdateNoArgs()
    {
        $date = new \DateTime();
        $actual = $this->_object->ddate();
        $expected = $this->_object->ddate('%{%A, %B %d,%} %Y YOLD', $date->format('dmY'));
        self::assertEquals($expected, $actual);
    }

    /**
     * Test getSupportedFormatFields().
     */
    public function testGetSupportedFormatFields()
    {
        $expected = array(
            '%A' => 'Full name of the day of the week (e.g. Sweetmorn)',
            '%a' => 'Abbreviated name of the day of the week (e.g. SM)',
            '%B' => 'Full name of the season (e.g. Chaos)',
            '%b' => 'Abbreviated name of the season (e.g. Chs)',
            '%d' => 'Ordinal number of the day in the season (e.g. 23)',
            '%e' => 'Cardinal number of the day in the season (e.g. 23rd)',
            '%Y' => 'A full numeric representation of a year, 4 digits',
            '%H' => 'Name of current Holyday, if any',
            '%N' => 'Magic code to prevent rest of the format being printed unless today is a Holyday',
            '%n' => 'Newline',
            '%t' => 'Tab',
            '%X' => 'Number of days remaining until X-Day.',
            '%{' => 'Enclose the part of the string which is to be replaced with "St. Tib\'s Day" if the current day is St. Tib\'s Day (start delimiter)',
            '%}' => 'Enclose the part of the string which is to be replaced with "St. Tib\'s Day" if the current day is St. Tib\'s Day (end delimiter)'
        );
        $actual = $this->_object->getSupportedFormatStringFields();
        self::assertEquals($expected, $actual);
    }

}
