<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

namespace EmperorNortonCommands\lib\Internal\Holydays;

use EmperorNortonCommands\lib\Internal\Holydays\NoFixedDateHolydayInterface;
use EmperorNortonCommands\lib\Internal\Legacy\Value;

/**
 * Go Topless Day is the Sunday nearest 19th of Bureaucracy (26th of August),
 * Women's Equality Day.
 *
 * @package EmperorNortonCommands\lib\Ddate
 * @internal
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
