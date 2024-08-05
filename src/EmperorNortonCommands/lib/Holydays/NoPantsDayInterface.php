<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

namespace EmperorNortonCommands\lib\Holydays;

use EmperorNortonCommands\lib\Value;

/**
 * No Pants Day is the first Friday in May.
 *
 * @package EmperorNortonCommands\lib\Ddate
 * @internal
 */
class NoPantsDayInterface implements NoFixedDateHolydayInterface
{
    /**
     * Checks if given ddate value is No Pants Day.
     *
     * @param Value $ddate
     * @return boolean
     */
    public function is(Value $ddate)
    {
        if ('05' != $ddate->getGregorian()->format('m')) {
            return false;
        }
        if ('Fri' != $ddate->getGregorian()->format('D')) {
            return false;
        }
        if ($ddate->getGregorian()->format('d') > 7) {
            return false;
        }

        return true;
    }
}
