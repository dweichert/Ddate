<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

namespace EmperorNortonCommands\lib\Holydays;

use EmperorNortonCommands\lib\Value;

/**
 * Some Holydays occur on days that require calculation rather than on a fixed
 * date of the year.
 *
 * @package EmperorNortonCommands\lib\Ddate
 * @internal
 */
interface NoFixedDateHolydayInterface
{
    /**
     * Checks if given ddate value is the Holyday specified by the implementing class.
     *
     * @param Value $ddate
     * @return boolean
     */
    public function is(Value $ddate);
}
