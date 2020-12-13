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
        $funFriday = new FunFriday();
        if ($funFriday->is($ddate)) {
            $holidays[] = parent::getName('FunFriday', $locale);
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
}
