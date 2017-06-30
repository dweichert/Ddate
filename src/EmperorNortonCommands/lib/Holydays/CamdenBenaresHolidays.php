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
 * Class CamdenBenaresHolidays
 * @package EmperorNortonCommands\lib\Holydays
 */
class CamdenBenaresHolidays extends Holydays
{
    const KEY = 'camden_benares';

    /**
     * @inheritdoc
     */
    public function getHolyday(Value $ddate, $locale)
    {
        $holidays = array();
        if ($this->isFunFriday($ddate))
        {
            if ('en' == $locale) {
                $holidays[] = 'Fun Friday';
            } else {
                $holidays[] = 'Vergnügungsfreitag';
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
        return __DIR__ . '/../locale/' . $locale . '/data/camden_benares_holidays.xml';
    }

    /**
     * Fun Friday is the fifth Friday in a month that has five Fridays.
     *
     * @return string
     */
    private function isFunFriday(Value $value)
    {
        if ($value->getGregorian()->format('j') < 29) {
            return false;
        }
        if ($value->getGregorian()->format('D') != 'Fri') {
            return false;
        }

        return true;
    }
}
