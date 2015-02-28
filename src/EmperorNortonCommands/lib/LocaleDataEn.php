<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

namespace EmperorNortonCommands\lib;

/**
 * Class En.
 *
 * Provides English messages for Discordian dates.
 *
 * @package EmperorNortonCommands\lib
 */
class LocaleDataEn extends LocaleData
{
    /**
     * Discordian Holydays.
     *
     * @var string[]
     */
    protected $_holydays = array(
        '0501' => 'Mungday',
        '1902' => 'Chaoflux',
        '2902' => 'St. Tib\'s Day',
        '1903' => 'Mojoday',
        '0305' => 'Discoflux',
        '3105' => 'Syaday',
        '1507' => 'Conflux',
        '1208' => 'Zaraday',
        '2609' => 'Bureflux',
        '2410' => 'Maladay',
        '0812' => 'Afflux'
    );

    /**
     * Full names of the day of the week.
     *
     * @var string[]
     */
    protected $_days = array('Sweetmorn', 'Boomtime', 'Pungenday', 'Prickle-Prickle', 'Setting Orange');

    /**
     * Abbreviated names of the day of the week.
     *
     * @var string[]
     */
    protected $_abbrevDays = array('SM', 'BT', 'PD', 'PP', 'SO');

    /**
     * Full names of the season.
     *
     * @var string[]
     */
    protected $_seasons = array('Chaos', 'Discord', 'Confusion', 'Bureaucracy', 'The Aftermath');

    /**
     * Abbreviated names of the seasons.
     *
     * @var string[]
     */
    protected $_abbrevSeasons = array('Chs', 'Dsc', 'Cfn', 'Bcy', 'Afm');

}