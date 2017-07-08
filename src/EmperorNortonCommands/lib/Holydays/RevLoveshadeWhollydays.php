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
        if ($this->isGoToplessDay($ddate))
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
        if ($this->isNoPantsDay($ddate))
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

    /**
     * Is it Go Topless Day?
     *
     * @param Value $ddate
     * @return bool
     */
    private function isGoToplessDay(Value $ddate)
    {
        if ('08' != $ddate->getGregorian()->format('m'))
        {
            return false;
        }
        if ('Sun' != $ddate->getGregorian()->format('D'))
        {
            return false;
        }
        if ($ddate->getGregorian()->format('d') > 29)
        {
            return false;
        }
        if ($ddate->getGregorian()->format('d') < 23)
        {
            return false;
        }

        return true;
    }

    /**
     * Is it No Pants Day?
     *
     * @param Value $ddate
     * @return bool
     */
    private function isNoPantsDay(Value $ddate)
    {
        if ('05' != $ddate->getGregorian()->format('m'))
        {
            return false;
        }
        if ('Fri' != $ddate->getGregorian()->format('D'))
        {
            return false;
        }
        if ($ddate->getGregorian()->format('d') > 7)
        {
            return false;
        }

        return true;
    }
}
