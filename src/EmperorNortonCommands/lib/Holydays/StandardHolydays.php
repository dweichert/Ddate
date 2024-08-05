<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

namespace EmperorNortonCommands\lib\Holydays;

use EmperorNortonCommands\lib\Holydays;

/**
 * The standard holydays defined in Principia Discordia, 00034.
 *
 * @package EmperorNortonCommands\lib\Ddate
 * @internal
 */
class StandardHolydays extends Holydays
{
    public const string KEY = 'standard';

    /**
     * @inheritdoc
     */
    protected function getPathToXML($locale)
    {
        return __DIR__ . '/../locale/' . $locale . '/data/standard_holydays.xml';
    }
}
