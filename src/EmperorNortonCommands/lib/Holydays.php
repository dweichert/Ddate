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
    const KEY = 'holydays';

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
        $xpath = $this->getXpath($locale);

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
        return static::KEY;
    }

    /**
     * Get path to locale specific XML data file.
     *
     * @param  string $locale
     * @return string
     */
    abstract protected function getPathToXML($locale);

  /**
   * Get name of Holyday by key.
   *
   * @param string $key
   * @param string $locale
   * @return string
   */
    protected function getName($key, $locale)
    {
        $holydayNodeList = $this->getXpath($locale)->query("//h:name[..//h:irregular='$key']");

        return $holydayNodeList->item(0)->textContent;
    }

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
     * Get Holyday from XML by Gregorian day and season.
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

    /**
     * Get DOMXPath Object for XML containing translations.
     *
     * @param $locale
     * @return DOMXPath
     */
    private function getXpath($locale)
    {
        $domDocument = new DOMDocument();
        $domDocument->load($this->getPathToXML($locale));
        $xpath = new DOMXPath($domDocument);
        $xpath->registerNamespace('h', 'https://davidweichert.de/ns/ddate-holyday');

        return $xpath;
    }

}
