<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

namespace EmperorNortonCommands\lib;

/**
 * Class Holydays
 * @package EmperorNortonCommands\lib
 */
abstract class Holydays
{
    /**
     * Array of Holydays.
     *
     * Keys are composed of the Gregorian day and month (2 digits). Values are the
     * Holyday's name in the given language.
     *
     * @var mixed
     */
    protected $holydays = array();

    /**
     * Get Holyday.
     *
     * Returns the name of the Holyday if there is a Holyday on given date,
     * else returns empty string.
     *
     * @param Value $ddate
     * @return string
     */
    public function getHolyday(Value $ddate)
    {
        $key = $ddate->getGregorian()->format('d')
             . $ddate->getGregorian()->format('m');

        if (isset($this->holydays[$key])) {
            return $this->holydays[$key];
        }

        return '';
    }
}
