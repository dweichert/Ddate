<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

namespace EmperorNortonCommands\tests\lib;

use EmperorNortonCommands\lib\Ddate;
use PHPUnit_Framework_TestCase;

/**
 * Class DdateLocalizedTest.
 *
 * @package EmperorNortonCommands\tests\lib
 */
class DdateHolydayTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Ddate
     */
    private $object;

    protected function setUp()
    {
        $this->object = new Ddate();
    }

    /**
     * @dataProvider turnOffStandardHolydaysProvider
     */
    public function testTurnOffStandardHolydays($gregorian, $discordian, $format, $locale)
    {
        $actual = $this->object->ddate($format, $gregorian, $locale);
        self::assertEquals($discordian, $actual);
    }

    public function turnOffStandardHolydaysProvider()
    {
        return array(
            'no format' => array('26092013', "Prickle-Prickle, Bureaucracy 50, 3179 YOLD", null, null),
            'display conflux' => array('15072013', "It's Sweetmorn, the 50th of Confusion, 3179. \nCelebrate Conflux", "It's %{%A, the %e of %B%}, %Y. %N%nCelebrate %H", ""),
            'standard holydays turned off' => array('15072013', "It's Sweetmorn, the 50th of Confusion, 3179.", "It's %{%A, the %e of %B%}, %Y.%N%nCelebrate %H %1", ""),
            'display conflux German' => array('15072013', "Heute ist Süßmorgen, der 50. der Unordnung, 3179. \nFeiert Unfluss", "Heute ist %{%A, der %e der %B%}, %Y. %N%nFeiert %H", "de"),
            'standard holydays turned off German' => array('15072013', "Heute ist Süßmorgen, der 50. der Unordnung, 3179.", "%1Heute ist %{%A, der %e der %B%}, %Y.%N%nFeiert %H", "de"),
        );
    }

    /**
     * @dataProvider funFridayProvider
     */
    public function testFunFriday($gregorian, $discordian, $format)
    {
        self::assertEquals($discordian, $this->object->ddate($format, $gregorian));
    }

    public function funFridayProvider()
    {
        return array(
            array('31032017',"It's Setting Orange, the 17th of Discord, 3183.\nCelebrate Fun Friday", "It's %{%A, the %e of %B%},%2 %Y.%N%nCelebrate %H"),
            array('30062017',"It's Sweetmorn, the 35th of Confusion, 3183.\nCelebrate Fun Friday", "It's %{%A, the %e of %B%},%2 %Y.%N%nCelebrate %H"),
            array('29092017',"It's Boomtime, the 53rd of Bureaucracy, 3183.\nCelebrate Fun Friday", "It's %{%A, the %e of %B%},%2 %Y.%N%nCelebrate %H"),
            array('29122017',"It's Pungenday, the 71st of The Aftermath, 3183.\nCelebrate Fun Friday", "It's %{%A, the %e of %B%},%2 %Y.%N%nCelebrate %H"),
            array('29032013',"It's Pungenday, the 15th of Discord, 3179.\nCelebrate Fun Friday", "It's %{%A, the %e of %B%},%2 %Y.%N%nCelebrate %H"),
            array('31052013',"It's Sweetmorn, the 5th of Confusion, 3179.\nCelebrate Syaday and Fun Friday", "It's %{%A, the %e of %B%},%2 %Y.%N%nCelebrate %H"),
            array('30082013',"It's Boomtime, the 23rd of Bureaucracy, 3179.\nCelebrate Fun Friday and St. Scrivener’s Day", "It's %{%A, the %e of %B%},%2 %Y.%N%nCelebrate %H"),
            array('29112013',"It's Pungenday, the 41st of The Aftermath, 3179.\nCelebrate Fun Friday", "It's %{%A, the %e of %B%},%2 %Y.%N%nCelebrate %H"),
            array('31032000',"It's Setting Orange, the 17th of Discord, 3166.\nCelebrate Fun Friday", "It's %{%A, the %e of %B%},%2 %Y.%N%nCelebrate %H"),
            array('30062000',"It's Sweetmorn, the 35th of Confusion, 3166.\nCelebrate Fun Friday", "It's %{%A, the %e of %B%},%2 %Y.%N%nCelebrate %H"),
            array('29092000',"It's Boomtime, the 53rd of Bureaucracy, 3166.\nCelebrate Fun Friday", "It's %{%A, the %e of %B%},%2 %Y.%N%nCelebrate %H"),
            array('29122000',"It's Pungenday, the 71st of The Aftermath, 3166.\nCelebrate Fun Friday", "It's %{%A, the %e of %B%},%2 %Y.%N%nCelebrate %H"),
            array('31012020',"It's Sweetmorn, the 31st of Chaos, 3186.\nCelebrate Fun Friday", "It's %{%A, the %e of %B%},%2 %Y.%N%nCelebrate %H"),
            array('29052020',"It's Prickle-Prickle, the 3rd of Confusion, 3186.\nCelebrate Fun Friday", "It's %{%A, the %e of %B%},%2 %Y.%N%nCelebrate %H"),
            array('31072020',"It's Boomtime, the 66th of Confusion, 3186.\nCelebrate Fun Friday", "It's %{%A, the %e of %B%},%2 %Y.%N%nCelebrate %H"),
            array('30102020',"It's Pungenday, the 11th of The Aftermath, 3186.\nCelebrate Fun Friday", "It's %{%A, the %e of %B%},%2 %Y.%N%nCelebrate %H"),
        );
    }

    /**
     * @dataProvider camdenBenaresHolidaysProvider
     */
    public function testCamdenBenaresHolidays($gregorian, $discordian, $format)
    {
        self::assertEquals($discordian, $this->object->ddate($format, $gregorian));
    }

    public function camdenBenaresHolidaysProvider()
    {
        return array(
            array('01011997', "It's Sweetmorn, the 1st of Chaos, 3163.\nCelebrate Bogey's Day", "It's %{%A, the %e of %B%},%2 %Y.%N%nCelebrate %H"),
            array('15011915', "It's Setting Orange, the 15th of Chaos, 3081.\nCelebrate St. Afrodite's Day", "%2It's %{%A, the %e of %B%},%2 %Y.%N%nCelebrate %H"),
            array('23011928', "It's Pungenday, the 23rd of Chaos, 3094.\nCelebrate St. Bobcat's Day", "It's %{%A, the %e of %B%},%2 %Y.%N%nCelebrate %H"),
            array('03031999', "It's Boomtime, the 62nd of Chaos, 3165.\nCelebrate Pass Day", "It's %{%A, the %e of %B%}, %Y.%N%nCelebrate%2 %H"),
            array('04043000', "It's Prickle-Prickle, the 21st of Discord, 4166.\nCelebrate Square Day", "It's %{%A, the %e of %B%},%2 %Y.%N%nCelebrate %H"),
            array('01052016', "It's Sweetmorn, the 48th of Discord, 3182.\nCelebrate Adam Weishaupt’s Day", "It's %{%A, the %e of %B%},%2 %Y.%N%nCelebrate %H"),
            array('23052000', "It's Pungenday, the 70th of Discord, 3166.\nCelebrate Buddha’s Birthday", "It%2's %{%A, the %e of %B%}, %Y.%N%nCelebrate %H"),
            array('05062020', "It's Sweetmorn, the 10th of Confusion, 3186.\nCelebrate Golden Apple Day", "It's %{%A, the %e of %B%},%2 %Y.%N%nCelebrate %H"),
            array('14072019', "It's Setting Orange, the 49th of Confusion, 3185.\nCelebrate St. Merde’s Day", "It's %{%A, the %e of %B%},%2 %Y.%N%nCelebrate %H"),
            array('23082018', "It's Setting Orange, the 16th of Bureaucracy, 3184.\nCelebrate Nancy Fancymanner’s Birthday", "It's %{%A, the %e of %B%},%2 %Y.%N%nCelebrate %H"),
            array('17092017', "It's Setting Orange, the 41st of Bureaucracy, 3183.\nCelebrate Emperor Norton’s Day", "It's %{%A, %2the %e of %B%}, %Y.%N%nCelebrate %H"),
            array('30112016', "It's Prickle-Prickle, the 42nd of The Aftermath, 3182.\nCelebrate Early Lunch Day", "It's %{%A, the %e of %B%},%2 %Y.%N%nCelebrate %H"),
        );
    }

    /**
     * @dataProvider funFridayDeProvider
     */
    public function testFunFridayDe($gregorian, $discordian, $format)
    {
        self::assertEquals($discordian, $this->object->ddate($format, $gregorian, 'de'));
    }

    public function funFridayDeProvider()
    {
        return array(
            array('30032323', "Heute ist Prickel-Prickel, 16. der Zweitracht 3489, heute ist: Vergnügungsfreitag.", "Heute ist %{%A, %e %C %Y%}%2%N, heute ist: %H."),
            array('31082323', "Heute ist Stichtag, 24. der Beamtenherrschaft 3489, heute ist: Vergnügungsfreitag.", "Heute ist %{%A, %e %C %Y%}%2%N, heute ist: %H."),
            array('30111990', "Heute ist Prickel-Prickel, 42. des Grummets 3156, heute ist: Vergnügungsfreitag und Tag des frühen Mittagessens.", "Heute ist %{%A, %e %C %Y%}%2%N, heute ist: %H."),
        );
    }

    /**
     * @dataProvider revDrJonSwabeysWhollydaysProvider
     */
    public function testRevDrJonSwabeysWhollydays($gregorian, $discordian, $format, $locale)
    {
        self::assertEquals($discordian, $this->object->ddate($format, $gregorian, $locale));
    }

    public function revDrJonSwabeysWhollydaysProvider()
    {
        return array(
            array('27012017', "Heute ist Blütezeit, 27. der Verwirrung 3183, heute ist: Faultiertag.", "Heute ist %{%A, %e %C %Y%}%3%N, heute ist: %H.", 'de'),
            array('27012017', "Heute ist Blütezeit, 27. der Verwirrung 3183, heute ist: Faultiertag.", "Heute ist %{%A, %e %C %Y%}%3%1%N, heute ist: %H.", 'de'),
            array('07082017', "Heute ist Prickel-Prickel, 73. der Unordnung 3183, heute ist: Tag des Auges.", "Heute ist %{%A, %e %C %Y%}%3%1%N, heute ist: %H.", 'de'),
            array('31052013', "It's Sweetmorn, the 5th of Confusion, 3179.\nCelebrate Syaday and Fun Friday", "It's %{%A, the %e of %B%},%1%2%3 %Y.%N%nCelebrate %H", 'en'),
            array('03052013', "It's Pungenday, the 50th of Discord, 3179.\nCelebrate Discoflux", "It's %{%A, the %e of %B%},%1%2%3 %Y.%N%nCelebrate %H", 'en'),
            array('31122004', "It's Setting Orange, the 73rd of The Aftermath, 3170.\nCelebrate Fun Friday and Eye Day", "It's %{%A, the %e of %B%},%1%2%3 %Y.%N%nCelebrate %H", 'en'),
            array('03092017', "It's Sweetmorn, the 27th of Bureaucracy, 3183. Celebrate Slothday", "It's %{%A, the %e of %B%},%1%2%3 %Y.%N Celebrate %H", 'en'),
        );
    }

    /**
     * @dataProvider revLoveshadesWhollydaysProvider
     */
    public function testRevLoveshadesWhollydays($gregorian, $discordian, $format, $locale)
    {
        self::assertEquals($discordian, $this->object->ddate($format, $gregorian, $locale));
    }

    public function revLoveshadesWhollydaysProvider()
    {
        return array(
            array('01011995', "It's Sweetmorn, the 1st of Chaos, 3161. Celebrate Bogey's Day and Nude Year's Day", "It's %{%A, the %e of %B%},%1%2%3%4 %Y.%N Celebrate %H", 'en'),
            array('10011996', "It's Setting Orange, the 10th of Chaos, 3162. Celebrate Backwards Day (Reformed) and Binary Day", "It's %{%A, the %e of %B%},%1%2%3%4 %Y.%N Celebrate %H", 'en'),
            array('30031997', "It's Prickle-Prickle, the 16th of Discord, 3163.\nCelebrate Erister", "It's %{%A, the %e of %B%},%1%2%3%4 %Y.%N%nCelebrate %H", 'en'),
            array('23042000', "It's Pungenday, the 40th of Discord, 3166.\nCelebrate Erister", "It's %{%A, the %e of %B%},%1%2%3%4 %Y.%N%nCelebrate %H", 'en'),
            array('16042006', "It's Sweetmorn, the 33rd of Discord, 3172.\nCelebrate Erister", "It's %{%A, the %e of %B%},%1%2%3%4 %Y.%N%nCelebrate %H", 'en'),
            array('05042015', "It's Setting Orange, the 22nd of Discord, 3181.\nCelebrate Be Kind To Tourists Day and Erister", "It's %{%A, the %e of %B%},%1%2%3%4 %Y.%N%nCelebrate %H", 'en'),
            array('31052013', "It's Sweetmorn, the 5th of Confusion, 3179.\nCelebrate Syaday, Fun Friday and Gulikday/Fearless Fred Day", "It's %{%A, the %e of %B%},%1%2%3%4 %Y.%N%nCelebrate %H", 'en'),
            array('30122017', "Heute ist Prickel-Prickel, 72. des Grummets 3183, heute ist: Vorabend des Neujahrsabends.", "%4Heute ist %{%A, %e %C %Y%}%3%N, heute ist: %H.", 'de'),
            array('10011901', "Heute ist Orangewerdend, 10. der Verwirrung 3067, heute ist: Rückwärtstag (reformiert) und Binärtag.", "%4Heute ist %{%A, %e %C %Y%}%3%N, heute ist: %H.", 'de'),
            array('07041901', "Heute ist Blütezeit, 24. der Zweitracht 3067, heute ist: Eristern.", "%4Heute ist %{%A, %e %C %Y%}%3%N, heute ist: %H.", 'de'),
            array('27082017', "Today is Prickle-Prickle, 20th Bureaucracy 3183, celebrate: Go Topless Day.", "%4Today is %{%A, %e %B %Y%}%3%N, celebrate: %H.", 'en'),
            array('27082017', "Heute ist Prickel-Prickel, 20. der Beamtenherrschaft 3183, heute ist: Obenohne Tag.", "%4Heute ist %{%A, %e %C %Y%}%3%N, heute ist: %H.", 'de'),
            array('26082018', "Heute ist Stichtag, 19. der Beamtenherrschaft 3184, heute ist: Obenohne Tag.", "%4Heute ist %{%A, %e %C %Y%}%3%N, heute ist: %H.", 'de'),
            array('23082026', "Today is Setting Orange, 16th Bureaucracy 3192, celebrate: Go Topless Day.", "%4Today is %{%A, %e %B %Y%}%3%N, celebrate: %H.", 'en'),
            array('23082027', "Today is Setting Orange, 16th Bureaucracy 3193", "%4Today is %{%A, %e %B %Y%}%3%N, celebrate: %H.", 'en'),
            array('30082026', "Today is Boomtime, 23rd Bureaucracy 3192", "%4Today is %{%A, %e %B %Y%}%3%N, celebrate: %H.", 'en'),
            array('16082026', "Today is Pungenday, 9th Bureaucracy 3192", "%4Today is %{%A, %e %B %Y%}%3%N, celebrate: %H.", 'en'),
            array('06051910', "Today is Sweetmorn, 53rd Discord 3076, celebrate: No Pants Day.", "%4Today is %{%A, %e %B %Y%}%3%N, celebrate: %H.", 'en'),
            array('03051912', "Today is Pungenday, 50th Discord 3078, celebrate: Discoflux and No Pants Day.", "%4Today is %{%A, %e %B %Y%}%3%N, celebrate: %H.", 'en'),
            array('03052041', "Heute ist Stichtag, 50. der Zweitracht 3207, heute ist: Zweifluss und Untenohne Tag.", "%4Heute ist %{%A, %e %C %Y%}%3%N, heute ist: %H.", 'de'),
            array('03052042', "Heute ist Stichtag, 50. der Zweitracht 3208, heute ist: Zweifluss.", "%4Heute ist %{%A, %e %C %Y%}%3%N, heute ist: %H.", 'de'),
        );
    }
}
