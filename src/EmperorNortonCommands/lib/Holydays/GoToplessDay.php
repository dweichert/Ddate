<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

namespace EmperorNortonCommands\lib\Holydays;

use EmperorNortonCommands\lib\Value;

/**
 * Class GoToplessDay
 *
 * Sunday nearest 19th of Bureaucracy (26th of August), Women's Equality Day.
 *
 * @package EmperorNortonCommands\lib\Holydays
 */
class GoToplessDay implements NoFixedDateHolydayInterface
{
    /**
     * Checks if given ddate value is Go Topless Day.
     *
     * @param Value $ddate
     * @return boolean
     */
    public function is(Value $ddate)
    {
        if ('08' != $ddate->getGregorian()->format('m')) {
            return false;
        }
        if ('Sun' != $ddate->getGregorian()->format('D')) {
            return false;
        }
        if ($ddate->getGregorian()->format('d') > 29) {
            return false;
        }
        if ($ddate->getGregorian()->format('d') < 23) {
            return false;
        }

        return true;
    }
}
