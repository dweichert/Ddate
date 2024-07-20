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
 * @see src/EmperorNortonCommands/lib/locale/en/data/camden_benares_holidays.xml
 * @package EmperorNortonCommands\lib\Ddate
 * @internal
 */
class CamdenBenaresHolidays extends Holydays
{
    public const string KEY = 'camden_benares';

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
