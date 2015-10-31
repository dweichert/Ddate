<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

namespace EmperorNortonCommands\lib;

/**
 * Class GermanStandardFormatter.
 * @package EmperorNortonCommands\lib
 */
class GermanStandardFormatter extends EnglishStandardFormatter
{
    /**
     * Supported format string fields and description.
     *
     * @var string[]
     */
    protected $_supportedFormatStringFields = array(
        '%A' => 'Vollständiger Name des Woechentages (z.B. Süßtag)',
        '%a' => 'Abgekürzter Name des Wochentages (z.B. SU)',
        '%B' => 'Vollständiger Name der Saison (z.B. Verwirrung)',
        '%b' => 'Abgekürzter Name der Saison (z.B. Ve)',
        '%d' => 'Tag der Saison als Ordinalzahl (z.B. 23)',
        '%e' => 'Tag der Saison als Kardinalzahl (z.B. 23.)',
        '%Y' => 'Volle numerische Repräsentation des Jahrs, vierstellig',
        '%H' => 'Name des heiligen Tages (wenn es einer ist)',
        '%N' => 'Magischer Code, um die Ausgabe des restlichen Formates zu unterbinden, es sei denn es ist ein heiliger Tag',
        '%n' => 'Neue Zeile',
        '%t' => 'Tabulator',
        '%X' => 'Anzahl der Tage bis zum Tag X.',
        '%{' => 'Schließt den Teil des Datums ein, der mit "St. Tibs Tag" ersetzt wird, wenn es St. Tibs Tag ist (Anfang)',
        '%}' => 'Schließt den Teil des Datums ein, der mit "St. Tibs Tag" ersetzt wird, wenn es St. Tibs Tag ist (Ende)'
    );

    /**
     * Translations for Holydays.
     *
     * @var string[]
     */
    protected $_holydayTranslations = array(
        'Mungday' => 'Mungtag',
        'Chaoflux' => 'Wirrfluss',
        'Mojoday' => 'Mojotag',
        'Discoflux' => 'Zweifluss',
        'Syaday' => 'Syatag',
        'Conflux' => 'Unfluss',
        'Zaraday' => 'Zaratag',
        'Bureflux' => 'Beamtenfluss',
        'Maladay' => 'Malatag',
        'Afflux' => 'Ausfluss'
    );

    /**
     * Cardinal numbers.
     *
     * @var string[]
     */
    protected $_cardinalNumbers = array(
        'Erster', 'Zweiter', 'Dritter', 'Vierter', 'Fünfter', 'Sechster', 'Siebter', 'Achter', 'Neunter', 'Zehnter',
        'Elfter', 'Zwölfter', 'Dreizehnter', 'Vierzehnter', 'Fünfzehnter', 'Sechzehnter', 'Siebzehnter', 'Achtzehnter', 'Neunzehnter', 'vierzigster',
        'Einundfünfzigster', 'Zweiundzwanzigster', 'Dreiundzwanzigster', 'Vierundzwanzigster', 'Fünfundzwanzigster', 'Sechsundzwanzigster', 'Siebenundzwanzigster', 'Achtundzwanzigster', 'Neunundzwanzigster', 'Dreißigster',
        'Einunddreißigster', 'Zweiunddreißigster', 'Dreiunddreißigster', 'Vierunddreißigster', 'Fünfunddreißigster', 'Sechsunddreißigster', 'Siebenunddreißigster', 'Achtunddreißigster', 'Neununddreißigster', 'Vierzigster',
        'Einundvierzigster', 'Zweiundvierzigster', 'Dreiundvierzigster', 'Vierundvierzigster', 'Fünfundvierzigster', 'Sechsundvierzigster', 'Siebenundvierzigster', 'Achtundvierzigster', 'Neunundvierzigster', 'Fünfzigster',
        'Einundfünfzigster', 'Zweiundfünfzigster', 'Dreiundfünfzigster', 'Vierundfünfzigster', 'Fünfundfünfzigster', 'Sechsundfünfzigster', 'Siebenundfünfzigster', 'Achtundfünfzigster', 'Neunundfünfzigster', 'Sechzigster',
        'Einundsechzigster', 'Zweiundsechzigster', 'Dreiundsechzigster', 'Vierundsechzigster', 'Fünfundsechzigster', 'Sechsundsechzigster', 'Siebenundsechzigster', 'Achtundsechzigster', 'Neunundsechzigster', 'Siebzigster',
        'Einundsiebzigster', 'Zweiundsiebzigster', 'Dreiundsiebzigster'
    );

    /**
     * Default format.
     *
     * @var string
     */
    protected $_defaultFormat = '%{%A, %B %d,%} %Y n. Gre.';

    /**
     * Full names of the day of the week.
     *
     * @var string[]
     */
    protected $_days = array('Süßtag', 'Dröhntag', 'Stechend', 'Prickel-Prickel', 'Orangewerdend');

    /**
     * Abbreviated names of the day of the week.
     *
     * @var string[]
     */
    protected $_abbrevDays = array('SU', 'DR', 'ST', 'PP', 'OW');

    /**
     * Full names of the season.
     *
     * @var string[]
     */
    protected $_seasons = array('Verwirrung', 'Zweitracht', 'Unordnung', 'Beamtenherrschaft', 'Ausklang');

    /**
     * Abbreviated names of the seasons.
     *
     * @var string[]
     */
    protected $_abbrevSeasons = array('Ve', 'Zw', 'Un', 'Be', 'Au');

    /**
     * No Holyday (msgid) string.
     *
     * @var string
     */
    protected $_noHolyday = 'kein heiliger Tag';

    /**
     * Replaces %{ and %} placeholders in given string.
     *
     * @param  string     $string
     * @param  DdateValue $ddate
     * @return string
     */
    protected function _replaceStTibsPlaceholders($string, DdateValue $ddate)
    {
        if (DdateValue::ST_TIBS_DAY === $ddate->getDay())
        {
            $string = preg_replace('/%{(.)*%}/', 'St. Tibs Tag', $string);
            return $string;
        }
        else
        {
            $string = str_replace('%{', '', $string);
            $string = str_replace('%}', '', $string);
            return $string;
        }
    }

    /**
     * Get cardinal day from ordinal day.
     *
     * Returns "FNORD" on St. Tibs Day.
     *
     * @param integer $day Discordian day of (ordinal) season
     * @return string
     */
    protected function _getCardinalDay($day)
    {
        if (DdateValue::ST_TIBS_DAY == $day)
        {
            return 'FNORD';
        }
        return $this->_cardinalNumbers[$day - 1];
    }

    /**
     * Get Holyday value.
     *
     * @param DdateValue $ddate
     * @return string
     */
    protected function _getHolyday(DdateValue $ddate)
    {
        return $this->_holydayTranslations[$ddate->getHolyday()];
    }

}