<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

namespace EmperorNortonCommands\lib\Holydays;

use EmperorNortonCommands\lib\Holydays;

/**
 * @see src/EmperorNortonCommands/lib/locale/en/data/rev_drjon_swabey_whollydays.xml
 * @package EmperorNortonCommands\lib\Ddate
 * @internal
 */
class RevDrJonSwabeyWhollydays extends Holydays
{
    public const string KEY = 'rev_drjon_swabey';

    /**
     * @inheritdoc
     */
    protected function getPathToXML($locale)
    {
        return __DIR__ . '/../locale/' . $locale . '/data/rev_drjon_swabey_whollydays.xml';
    }
}
