<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

namespace EmperorNortonCommands\lib\Internal\Formatter;

use EmperorNortonCommands\lib\DiscordianDate;
use EmperorNortonCommands\lib\FormatterInterface;
use EmperorNortonCommands\lib\Internal\Formatter\EnglishFormatter as EnglishStandardFormatter;
use EmperorNortonCommands\lib\Internal\Legacy\StTibsDay;
use EmperorNortonCommands\lib\Internal\Legacy\Value;
use EmperorNortonCommands\lib\Locale;
use EmperorNortonCommands\lib\XDay;

/**
 * @package EmperorNortonCommands\lib\Ddate
 * @internal
 */
class GermanFormatter extends EnglishStandardFormatter implements FormatterInterface
{
    /**
     * Cardinal numbers.
     *
     * @var string[]
     */
    protected $_cardinalNumbers = [
        'Erster', 'Zweiter', 'Dritter', 'Vierter', 'Fünfter', 'Sechster', 'Siebter', 'Achter', 'Neunter', 'Zehnter',
        'Elfter', 'Zwölfter', 'Dreizehnter', 'Vierzehnter', 'Fünfzehnter', 'Sechzehnter', 'Siebzehnter', 'Achtzehnter', 'Neunzehnter', 'vierzigster',
        'Einundfünfzigster', 'Zweiundzwanzigster', 'Dreiundzwanzigster', 'Vierundzwanzigster', 'Fünfundzwanzigster', 'Sechsundzwanzigster', 'Siebenundzwanzigster', 'Achtundzwanzigster', 'Neunundzwanzigster', 'Dreißigster',
        'Einunddreißigster', 'Zweiunddreißigster', 'Dreiunddreißigster', 'Vierunddreißigster', 'Fünfunddreißigster', 'Sechsunddreißigster', 'Siebenunddreißigster', 'Achtunddreißigster', 'Neununddreißigster', 'Vierzigster',
        'Einundvierzigster', 'Zweiundvierzigster', 'Dreiundvierzigster', 'Vierundvierzigster', 'Fünfundvierzigster', 'Sechsundvierzigster', 'Siebenundvierzigster', 'Achtundvierzigster', 'Neunundvierzigster', 'Fünfzigster',
        'Einundfünfzigster', 'Zweiundfünfzigster', 'Dreiundfünfzigster', 'Vierundfünfzigster', 'Fünfundfünfzigster', 'Sechsundfünfzigster', 'Siebenundfünfzigster', 'Achtundfünfzigster', 'Neunundfünfzigster', 'Sechzigster',
        'Einundsechzigster', 'Zweiundsechzigster', 'Dreiundsechzigster', 'Vierundsechzigster', 'Fünfundsechzigster', 'Sechsundsechzigster', 'Siebenundsechzigster', 'Achtundsechzigster', 'Neunundsechzigster', 'Siebzigster',
        'Einundsiebzigster', 'Zweiundsiebzigster', 'Dreiundsiebzigster'
    ];

    /**
     * Full names of the day of the week.
     *
     * @var string[]
     */
    protected $days = ['Süßmorgen', 'Blütezeit', 'Stichtag', 'Prickel-Prickel', 'Orangewerdend'];

    /**
     * Abbreviated names of the day of the week.
     *
     * @var string[]
     */
    protected $abbrevDays = ['SM', 'BZ', 'ST', 'PP', 'OW'];

    /**
     * Full names of the season.
     *
     * @var string[]
     */
    protected $seasons = ['Verwirrung', 'Zweitracht', 'Unordnung', 'Beamtenherrschaft', 'Grummet'];

    /**
     * Full names of the season.
     *
     * @var string[]
     */
    protected $seasonsGenitive = ['der Verwirrung', 'der Zweitracht', 'der Unordnung', 'der Beamtenherrschaft', 'des Grummets'];

    /**
     * Abbreviated names of the seasons.
     *
     * @var string[]
     */
    protected $abbrevSeasons = ['Ve', 'Zw', 'Un', 'Be', 'Au'];

    /**
     * No Holyday (msgid) string.
     *
     * @var string
     */
    protected $noHolyday = 'kein heiliger Tag';

    public function format(string|null $format, DiscordianDate $ddate): string
    {
        $value = new Value(
            $ddate->day instanceof StTibsDay ? Value::ST_TIBS_DAY : $ddate->day->value,
            $ddate->season instanceof StTibsDay ? Value::ST_TIBS_DAY : $ddate->season->value,
            $ddate->weekday instanceof StTibsDay ? Value::ST_TIBS_DAY : $ddate->weekday->value,
            $ddate->year->value,
            $ddate->gregorian,
        );
        $this->format = $format ?? DefaultFormatString::fromLocale(Locale::German)->value;
        $output = $this->format;
        $output = $this->replaceHolidaySetPlaceholders($output);
        $output = $this->replaceStTibsPlaceholders($output, $value);
        $output = $this->replaceHolidayPlaceholders($output, $value, 'de');
        $output = str_replace('%a', $this->getAbbreviatedWeekDayName($value->getWeekDay()), $output);
        $output = str_replace('%A', $this->getDiscordianWeekDayName($value->getWeekDay()), $output);
        $output = str_replace('%B', $this->getDiscordianSeasonName($value->getSeason()), $output);
        $output = str_replace('%b', $this->getAbbreviatedSeasonName($value->getSeason()), $output);
        $output = str_replace('%C', $this->getDiscordianSeasonNameGenitive($value->getSeason()), $output);
        $output = str_replace('%e', $this->getCardinalDay($value), $output);
        $output = str_replace('%E', $this->getCardinalDayFull($value->getDay()), $output);
        $output = str_replace('%d', $this->getOrdinalDay($value), $output);
        $output = str_replace('%Y', $value->getYear(), $output);
        $output = str_replace('%X', XDay::daysUntilRealXDay($ddate->gregorian), $output);
        $output = str_replace('%x', XDay::daysUntilOriginalXDay($ddate->gregorian), $output);
        $output = str_replace('%t', "\t", $output);
        $output = str_replace('%n', "\n", $output);

        return (string)$output;
    }

    public function getSupportedFormatStringFields(): array
    {
        return [
            '%A' => 'Vollständiger Name des Woechentages (z.B. Süßmorgen)',
            '%a' => 'Abgekürzter Name des Wochentages (z.B. SM)',
            '%B' => 'Vollständiger Name der Saison (z.B. Verwirrung)',
            '%b' => 'Abgekürzter Name der Saison (z.B. Ve)',
            '%C' => 'Vollständiger Name der Saison mit führendem bestimmten Artikel im Genitiv (z.B. der Verwirrung, des Grummets)',
            '%d' => 'Tag der Saison als Ordinalzahl (z.B. 23)',
            '%e' => 'Tag der Saison als Kardinalzahl (z.B. 23.)',
            '%E' => 'Tag der Saison als Kardinalzahl als Wort (z.B. Dreiundzwanzigster)',
            '%Y' => 'Volle numerische Repräsentation des Jahrs, vierstellig',
            '%H' => 'Name des heiligen Tages (wenn es einer ist)',
            '%N' => 'Magischer Code, um die Ausgabe des restlichen Formates zu unterbinden, es sei denn es ist ein heiliger Tag',
            '%n' => 'Neue Zeile',
            '%t' => 'Tabulator',
            '%X' => 'Anzahl der Tage seit dem / bis zum Tag X.',
            '%x' => 'Anzahl der Tage seit dem / bis zum ursprünglichen Tag X.',
            '%{' => 'Schließt den Teil des Datums ein, der mit "St. Tibs Tag" ersetzt wird, wenn es St. Tibs Tag ist (Anfang)',
            '%}' => 'Schließt den Teil des Datums ein, der mit "St. Tibs Tag" ersetzt wird, wenn es St. Tibs Tag ist (Ende)',
            '%1' => 'entferne Standardholydays (Feiertage)',
            '%2' => 'füge "Camden Beneres\' Holidays" zu den Holydays (Feiertagen) hinzu',
            '%3' => 'füge "Reverent DrJon Swabey\'s Whollydays" zu den Holydays (Feiertagen) hinzu',
            '%4' => 'füge "Reverent Loveshade\'s Whollydays" zu den Holydays (Feiertagen) hinzu',
        ];
    }


    /**
     * Replaces %{ and %} placeholders in given string.
     *
     * @param  string     $string
     * @param  Value $ddate
     * @return string
     */
    protected function replaceStTibsPlaceholders($string, Value $ddate)
    {
        if (Value::ST_TIBS_DAY === $ddate->getDay()) {
            $string = preg_replace('/%{(.)*%}/', 'St. Tibs Tag', $string);
            return $string;
        } else {
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
     * @param Value $ddate
     * @return string
     */
    protected function getCardinalDay($ddate)
    {
        if (Value::ST_TIBS_DAY == $ddate->getDay()) {
            return 'FNORD';
        }
        return $this->getOrdinalDay($ddate) . '.';
    }

    /**
     * Get cardinal day from ordinal day.
     *
     * Returns "FNORD" on St. Tibs Day. Returns cardinal day as word, not
     * numeral, e.g. "Dreiundzwanzigster".
     *
     * @param integer $day Discordian day of (ordinal) season
     * @return string
     */
    protected function getCardinalDayFull($day)
    {
        if (Value::ST_TIBS_DAY == $day) {
            return 'FNORD';
        }
        return $this->_cardinalNumbers[$day - 1];
    }

    /**
     * Get Discrodian season name in casus genitive.
     *
     * @param integer $season Discordian (ordinal) season
     * @return string
     */
    protected function getDiscordianSeasonNameGenitive($season)
    {
        if (Value::ST_TIBS_DAY == $season) {
            return 'FNORD';
        }
        $season = $season - 1;
        return (string)$this->seasonsGenitive[$season];
    }

    /**
     * Get localized string with all holydays.
     */
    protected function getHolydayString($holydays)
    {
        $holydayString = parent::getHolydayString($holydays);

        return str_replace('and', 'und', $holydayString);
    }
}
