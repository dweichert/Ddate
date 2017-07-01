<?php
/**
 * Created by PhpStorm.
 * User: dave
 * Date: 02.07.17
 * Time: 00:24
 */

namespace EmperorNortonCommands\lib\Holydays;

use DateTime;
use PHPUnit_Framework_TestCase;

class EristerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Erister
     */
    private $object;

    protected function setUp()
    {
        $this->object = new Erister();
    }

    /**
     * @dataProvider isEristerProvider
     */
    public function testIsErister($expectedTrue, $gregorian, $overrideCalendarExtension)
    {
        // Skip test with calendar extension in HHVM, because there is none and
        // in this case the fallback data will yield a false result, because
        // Erister data for years beyond X-Day (8661) is missing (see test case
        // 23.03.9000 - no extension).
        if (defined('HHVM_VERSION') && '23039000' == $gregorian && !$overrideCalendarExtension)
        {
            return;
        }

        if ($expectedTrue)
        {
            self::assertTrue($this->object->checkIsErister($this->getMockValue($gregorian), $overrideCalendarExtension));
        }
        else
        {
            self::assertFalse($this->object->checkIsErister($this->getMockValue($gregorian), $overrideCalendarExtension));
        }
    }

    public function isEristerProvider()
    {
        return array(
            '20.01.2000' => array(false, '20012000', false),
            '31.03.2002' => array(true, '31032002', false),
            '31.03.2002 - no extension' => array(true, '31032002', true),
            '31.03.2003' => array(false, '31032003', false),
            '31.03.2003 - no extension' => array(false, '31032003', true),
            // this would be Erister if X-Day does not occur
            '23.03.9000' => array(true, '23039000', false),
            // data not available in Erister class
            '23.03.9000 - no extension' => array(false, '31039000', true),
        );
    }

    private function getMockValue($gregorian)
    {
        $mock = $this->getMockBuilder('EmperorNortonCommands\lib\Value')
            ->setMethods(array('getGregorian'))
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects(self::any())
            ->method('getGregorian')
            ->will(self::returnValue(DateTime::createFromFormat('dmY', $gregorian)));

        return $mock;
    }
}
