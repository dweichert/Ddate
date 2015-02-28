<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

namespace EmperorNortonCommands\lib;

/**
 * Class LocaleData.
 * @package EmperorNortonCommands\lib
 */
abstract class LocaleData
{
    /**
     * Discordian Holydays.
     *
     * @var string[]
     */
    protected $_holydays = array();

    /**
     * Full names of the day of the week.
     *
     * @var string[]
     */
    protected $_days = array();

    /**
     * Abbreviated names of the day of the week.
     *
     * @var string[]
     */
    protected $_abbrevDays = array();

    /**
     * Full names of the season.
     *
     * @var string[]
     */
    protected $_seasons = array();

    /**
     * Abbreviated names of the seasons.
     *
     * @var string[]
     */
    protected $_abbrevSeasons = array();

    /**
     * Get Holydays.
     *
     * @return string[]
     */
    public function getHolydays()
    {
        return $this->_holydays;
    }

    /**
     * Get days.
     *
     * @return string[]
     */
    public function getDays()
    {
        return $this->_days;
    }

    /**
     * Get abbreviated days.
     *
     * @return string[]
     */
    public function getAbbrevDays()
    {
        return $this->_abbrevDays;
    }

    /**
     * Get seasons.
     *
     * @return string[]
     */
    public function getSeasons()
    {
        return $this->_seasons;
    }

    /**
     * Get abbreviated seasons.
     *
     * @return string[]
     */
    public function getAbbrevSeasons()
    {
        return $this->_abbrevSeasons;
    }

}