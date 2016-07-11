<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

namespace EmperorNortonCommands\lib\locale\en;

use EmperorNortonCommands\lib\Holydays;

/**
 * Class StandardHolydays
 * @package EmperorNortonCommands\lib\locale\en
 */
class StandardHolydays extends Holydays
{
    /**
     * Discordian Holydays.
     *
     * @var array
     */
    protected $holydays = array(
        '0501' => 'Mungday',
        '1902' => 'Chaoflux',
        '1903' => 'Mojoday',
        '0305' => 'Discoflux',
        '3105' => 'Syaday',
        '1507' => 'Conflux',
        '1208' => 'Zaraday',
        '2609' => 'Bureflux',
        '2410' => 'Maladay',
        '0812' => 'Afflux'
    );
}
