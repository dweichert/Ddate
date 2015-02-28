<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

namespace EmperorNortonCommands\lib;


class StandardFormatter extends DdateFormatter
{
    /**
     * Supported format string fields and description.
     *
     * @var string[]
     */
    protected $_supportedFormatStringFields = array(
        '%A' => 'Full name of the day of the week (e.g. Sweetmorn)',
        '%a' => 'Abbreviated name of the day of the week (e.g. SM)',
        '%B' => 'Full name of the season (e.g. Chaos)',
        '%b' => 'Abbreviated name of the season (e.g. Chs)',
        '%d' => 'Ordinal number of the day in the season (e.g. 23)',
        '%e' => 'Cardinal number of the day in the season (e.g. 23rd)',
        '%Y' => 'A full numeric representation of a year, 4 digits',
        '%H' => 'Name of current Holyday, if any',
        '%N' => 'Magic code to prevent rest of the format being printed unless today is a Holyday',
        '%n' => 'Newline',
        '%t' => 'Tab',
        '%X' => 'Number of days remaining until X-Day.',
        '%{' => 'Enclose the part of the string which is to be replaced with "St. Tib\'s Day" if the current day is St. Tib\'s Day (start delimiter)',
        '%}' => 'Enclose the part of the string which is to be replaced with "St. Tib\'s Day" if the current day is St. Tib\'s Day (end delimiter)'
    );
}