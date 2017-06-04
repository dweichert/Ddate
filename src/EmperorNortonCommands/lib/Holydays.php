<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

namespace EmperorNortonCommands\lib;
use DOMDocument;
use DOMXPath;
use RuntimeException;

/**
 * Class Holydays
 * @package EmperorNortonCommands\lib
 */
abstract class Holydays
{
    /**
     * Array of Holydays.
     *
     * Keys are composed of the Gregorian day and month (2 digits). Values are the
     * Holyday's name in the given language.
     *
     * @var mixed
     */
    protected $holydays = array();

    /**
     * Get Holyday.
     *
     * Returns the name of the Holyday if there is a Holyday on given date,
     * else returns empty string.
     *
     * @param  Value  $ddate
     * @param  string $locale
     * @return string
     * @throws RuntimeException
     */
    public function getHolyday(Value $ddate, $locale)
    {
        $domDocument = new DOMDocument();
        $domDocument->load($this->getPathToXML($locale));

        $xpath = new DOMXPath($domDocument);
        $xpath->registerNamespace('ddate', 'https://davidweichert.de/ns/ddate');

        $nodeList = $xpath->query('//@calendar');
        $calendar = $nodeList[0]->value;

        switch ($calendar)
        {
            case 'discordian':
                $season = str_pad($ddate->getSeason(), 2, '0', STR_PAD_LEFT);
                $day = str_pad($ddate->getDay(), 2, '0', STR_PAD_LEFT);
                $holyday = $xpath->query("//ddate:name[..//ddate:season='$season' and ..//ddate:day='$day']");
                if ($holyday->length)
                {
                    return (string)$holyday->item(0)->nodeValue;
                }
                return '';
                break;
            case 'gregorian':
                return '';
                break;
            default:
                throw new RuntimeException(sprintf('Unsupported calendar "%s".', $calendar));
        }
    }

    /**
     * Get path to locale specific XML data file.
     *
     * @param  string $locale
     * @return string
     */
    abstract protected function getPathToXML($locale);

}
