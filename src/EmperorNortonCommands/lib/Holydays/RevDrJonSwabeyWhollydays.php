<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */
namespace EmperorNortonCommands\lib\Holydays;

use EmperorNortonCommands\lib\Holydays;

/**
 * Class RevDrJonSwabeyHolydays
 * @package EmperorNortonCommands\lib\Holydays
 */
class RevDrJonSwabeyWhollydays extends Holydays
{
    const KEY = 'rev_drjon_swabey';

    /**
     * @inheritdoc
     */
    protected function getPathToXML($locale)
    {
        return __DIR__ . '/../locale/' . $locale . '/data/rev_drjon_swabey_whollydays.xml';
    }
}
