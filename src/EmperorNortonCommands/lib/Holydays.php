<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

namespace EmperorNortonCommands\lib;
use DOMDocument;
use DOMXPath;

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
     * @return string[]
     */
    public function getHolyday(Value $ddate, $locale)
    {
        $domDocument = new DOMDocument();
        $domDocument->load($this->getPathToXML($locale));

        $xpath = new DOMXPath($domDocument);
        $xpath->registerNamespace('h', 'https://davidweichert.de/ns/ddate-holyday');

        $calendar = $this->getCalendar($xpath);

        if ('discordian' === $calendar)
        {
            return $this->getHolydayDiscordian($ddate, $xpath);
        }

        return $this->getHolydayGregorian($ddate, $xpath);
    }

    /**
     * Returns key identifying the Holyday set.
     *
     * @return string
     */
    public static function getKey()
    {
        return '';
    }

    /**
     * Get path to locale specific XML data file.
     *
     * @param  string $locale
     * @return string
     */
    abstract protected function getPathToXML($locale);

    /**
     * Get Calendar.
     *
     * @param DOMXPath $xpath
     * @return string
     */
    private function getCalendar(DOMXPath $xpath)
    {
        $nodeList = $xpath->query('//@calendar');
        $calendar = (string)$nodeList->item(0)->nodeValue;

        return $calendar;
    }

    /**
     * Get Holyday from XML by Discordian day and season.
     *
     * @param Value $ddate
     * @param DOMXPath $xpath
     * @return string[]
     */
    private function getHolydayDiscordian(Value $ddate, DOMXPath $xpath)
    {
        $holydays = array();
        $season = str_pad($ddate->getSeason(), 2, '0', STR_PAD_LEFT);
        $day = str_pad($ddate->getDay(), 2, '0', STR_PAD_LEFT);
        $holydayNodeList = $xpath->query("//h:name[..//h:season='$season' and ..//h:day='$day']");
        foreach ($holydayNodeList as $element) {
            /** @var \DOMElement $element */
            $holydays[] = $element->textContent;
        }

        return $holydays;
    }

    /**
     * Get Holyday from XML by Discordian day and season.
     *
     * @param Value $ddate
     * @param DOMXPath $xpath
     * @return string[]
     */
    private function getHolydayGregorian(Value $ddate, DOMXPath $xpath)
    {
        $holydays = array();
        $month = $ddate->getGregorian()->format('m');
        $day = $ddate->getGregorian()->format('d');
        $holydayNodeList = $xpath->query("//h:name[..//h:month='$month' and ..//h:day='$day']");
        foreach ($holydayNodeList as $element) {
            /** @var \DOMElement $element */
            $holydays[] = $element->textContent;
        }

        return $holydays;
    }

}
