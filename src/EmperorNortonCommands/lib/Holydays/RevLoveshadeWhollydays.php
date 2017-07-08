<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */
namespace EmperorNortonCommands\lib\Holydays;


use EmperorNortonCommands\lib\Holydays;
use EmperorNortonCommands\lib\Value;

/**
 * Class RevLoveshadeWhollydays
 * @package EmperorNortonCommands\lib\Holydays
 */
class RevLoveshadeWhollydays extends Holydays
{
    const KEY = 'rev_loveshade';

    /**
     * @inheritdoc
     */
    public function getHolyday(Value $ddate, $locale)
    {
        $holidays = array();
        $erister = new Erister();
        if ($erister->is($ddate))
        {
            if ('en' == $locale)
            {
                $holidays[] = 'Erister';
            }
            else
            {
                $holidays[] = 'Eristern';
            }
        }
        $goToplessDay = new GoToplessDay();
        if ($goToplessDay->is($ddate))
        {
            if ('en' == $locale)
            {
                $holidays[] = 'Go Topless Day';
            }
            else
            {
                $holidays[] = 'Obenohne Tag';
            }
        }
        $noPantsDay = new NoPantsDay();
        if ($noPantsDay->is($ddate))
        {
            if ('en' == $locale)
            {
                $holidays[] = 'No Pants Day';
            }
            else
            {
                $holidays[] = 'Untenohne Tag';
            }
        }
        $holidays = array_merge($holidays, parent::getHolyday($ddate, $locale));

        return $holidays;
    }

    /**
     * @inheritdoc
     */
    protected function getPathToXML($locale)
    {
        return __DIR__ . '/../locale/' . $locale . '/data/rev_loveshade_whollydays.xml';
    }
}
