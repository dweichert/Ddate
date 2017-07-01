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
    const KEY = 'rev_drjon_swabey';

    /**
     * @inheritdoc
     */
    public function getHolyday(Value $ddate, $locale)
    {
        $holidays = array();
        $erister = new Erister();
        if ($erister->checkIsErister($ddate))
        {
            if ('en' == $locale) {
                $holidays[] = 'Erister';
            } else {
                $holidays[] = 'Eristern';
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
        return __DIR__ . '/../locale/' . $locale . '/data/rev_drjon_swabey_whollydays.xml';
    }
}
