<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

namespace EmperorNortonCommands\lib\Holydays;

use EmperorNortonCommands\lib\Value;

/**
 * Interface NoFixedDateHolyday.
 * @package EmperorNortonCommands\lib\Holydays
 */
interface NoFixedDateHolyday
{
    /**
     * Checks if given ddate value is Holyday.
     *
     * @param Value $ddate
     * @return boolean
     */
    public function is(Value $ddate);
}
