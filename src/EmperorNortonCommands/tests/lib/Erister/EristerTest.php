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
     * @dataProvider isEristerProvider
     */
    public function testIsErister($expectedTrue, $gregorian, $usePhpCalendarExt)
    {
        $object = new Erister($usePhpCalendarExt);
        if ($expectedTrue)
        {
            self::assertTrue($object->is($this->getMockValue($gregorian)));
        }
        else
        {
            self::assertFalse($object->is($this->getMockValue($gregorian)));
        }
    }

    public function isEristerProvider()
    {
        return array(
            '27.03.354' => array (true, '27030354', true),
            '27.03.354 - no extension' => array (true, '27030354', false),
            '31.03.1700' => array(true, '31031700', true),
            '31.03.1700 - no extension' => array(true, '31031700', false),
            '20.01.2000' => array(false, '20012000', true),
            '31.03.2002' => array(true, '31032002', true),
            '31.03.2002 - no extension' => array(true, '31032002', false),
            '31.03.2003' => array(false, '31032003', true),
            '31.03.2003 - no extension' => array(false, '31032003', false),
            '23.03.9000' => array(true, '23039000', true),
            '23.03.9000 - no extension' => array(true, '23039000', false),
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
