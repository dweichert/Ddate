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
            'no format' => array(26092013, "Prickle-Prickle, Bureaucracy 50, 3179 YOLD", null, null),
            'display conflux' => array(15072013, "It's Sweetmorn, the 50th of Confusion, 3179. \nCelebrate Conflux", "It's %{%A, the %e of %B%}, %Y. %N%nCelebrate %H", ""),
            'standard holydays turned off' => array(15072013, "It's Sweetmorn, the 50th of Confusion, 3179.", "It's %{%A, the %e of %B%}, %Y.%N%nCelebrate %H %1", ""),
            'display conflux German' => array(15072013, "Heute ist Süßmorgen, der 50. der Unordnung, 3179. \nFeiert Unfluss", "Heute ist %{%A, der %e der %B%}, %Y. %N%nFeiert %H", "de"),
            'standard holydays turned off German' => array(15072013, "Heute ist Süßmorgen, der 50. der Unordnung, 3179.", "%1Heute ist %{%A, der %e der %B%}, %Y.%N%nFeiert %H", "de"),
        );
    }
}
