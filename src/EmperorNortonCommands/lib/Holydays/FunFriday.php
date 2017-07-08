<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

namespace EmperorNortonCommands\lib\Holydays;

use EmperorNortonCommands\lib\Value;

/**
 * Class FunFriday
 *
 * Fun Friday is the fifth Friday in a month that has five Fridays.
 *
 * @package EmperorNortonCommands\lib\Holydays
 */
class FunFriday implements NoFixedDateHolyday
{
    /**
     * Checks if given ddate value is Fun Friday.
     *
     * @param Value $ddate
     * @return boolean
     */
    public function is(Value $ddate)
    {
        if ($ddate->getGregorian()->format('j') < 29) {
            return false;
        }
        if ($ddate->getGregorian()->format('D') != 'Fri') {
            return false;
        }

        return true;
    }
}
