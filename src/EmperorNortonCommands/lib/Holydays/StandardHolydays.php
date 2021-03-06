<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

namespace EmperorNortonCommands\lib\Holydays;

use EmperorNortonCommands\lib\Holydays;

/**
 * Class StandardHolydays
 * @package EmperorNortonCommands\lib\Holydays
 */
class StandardHolydays extends Holydays
{
    const KEY = 'standard';

    /**
     * @inheritdoc
     */
    protected function getPathToXML($locale)
    {
        return __DIR__ . '/../locale/' . $locale . '/data/standard_holydays.xml';
    }
}
