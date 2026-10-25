<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

declare(strict_types=1);

namespace EmperorNortonCommands\lib\Holydays;

use EmperorNortonCommands\lib\Value;

/**
 * Class Erister
 *
 * A Whollyday occurring on the first Sunday after the first full moon after
 * the vernal equinox in the northern hemisphere. Or it happens on Easter.
 *
 * @package EmperorNortonCommands\lib\Holydays
 */
class Erister implements NoFixedDateHolydayInterface
{
    /**
     * If set to true use PHP's calendar extension.
     *
     * @var boolean
     */
    private bool $usePhpCalendarExt = true;

    /**
     * Erister constructor.
     * @param boolean $usePhpCalendarExt OPTIONAL
     */
    public function __construct($usePhpCalendarExt = true)
    {
        if (false === $usePhpCalendarExt)
        {
            $this->usePhpCalendarExt = $usePhpCalendarExt;
        }
    }

    /**
     * Checks if given ddate value is Erister.
     *
     * @param Value $ddate
     * @return boolean
     */
    #[\Override]
    public function is(Value $ddate)
    {
        if (function_exists('easter_days') && $this->usePhpCalendarExt)
        {
            return $this->isEristerCalendarExtension($ddate);
        }
        else
        {
            return $this->isEristerNoCalendarExtension($ddate);
        }
    }

    /**
     * Perform check using calendar extension.
     *
     * @param Value $ddate
     * @return bool
     */
    private function isEristerCalendarExtension(Value $ddate): bool
    {
        $year = $ddate->getGregorian()->format('Y');
        $easterDays = easter_days((int)$year) + 21;
        if ($easterDays < 32)
        {
            $day = str_pad((string)$easterDays, 2, '0', STR_PAD_LEFT);
            $month = '03';
        }
        else
        {
            $day = str_pad((string)($easterDays - 31), 2, '0', STR_PAD_LEFT);
            $month = '04';
        }
        if ($ddate->getGregorian()->format('dmY') == $day . $month . $year)
        {
            return true;
        }

        return false;
    }

    /**
     * Perform check without relying on calendar extension.
     *
     * @param Value $ddate
     * @return bool
     */
    private function isEristerNoCalendarExtension(Value $ddate): bool
    {
        $year = (int)$ddate->getGregorian()->format('Y');

        if ($year < 1753)
        {
            [$month, $day] = $this->getEristerMeeusJulian($year);
        }
        else
        {
            [$month, $day] = $this->getEristerNewYorkCorrespondent($year);
        }

        $erister = str_pad((string)$day, 2, '0', STR_PAD_LEFT);
        $erister .= $month === 3 ? '03' : '04';
        $erister .= str_pad((string)$year, 4, '0', STR_PAD_LEFT);

        if ($erister === $ddate->getGregorian()->format('dmY'))
        {
            return true;
        }

        return false;
    }

    /**
     * Implements the anonymous algorithm subnmitted by "a New York correspondent"
     * to the journal "Nature" in 1876 aka Meeus/Jones/Butcher algorithm. Yields
     * the same result as PHP calendar extension's easter_days() method for the
     * year 1753 and onwards.
     *
     * @see https://en.wikipedia.org/wiki/Computus#Anonymous_Gregorian_algorithm
     * @param integer $year
     * @return int[] month and day
     */
    private function getEristerNewYorkCorrespondent(int $year): array
    {
        $a = $year % 19;
        $b = intdiv($year, 100);
        $c = $year % 100;
        $d = intdiv($b, 4);
        $e = $b % 4;
        $f = intdiv($b + 8, 25);
        $g = intdiv($b - $f + 1, 3);
        $h = (19 * $a + $b - $d - $g + 15) % 30;
        $i = intdiv($c, 4);
        $k = $c % 4;
        $l = (32 + 2 * $e + 2 * $i - $h - $k) % 7;
        $m = 7 * intdiv($a + 11 * $h + 22 * $l, 451);

        $month = intdiv($h + $l - $m + 114, 31);
        $day = (($h + $l - $m + 114) % 31) + 1;

        return [$month, $day];
    }

    /**
     * Implements Meeus' Julian algorithm. Yields the same result as PHP calendar
     * extensions's easter_days() method for years before 1753.
     *
     * @see https://en.wikipedia.org/wiki/Computus#Meeus.27_Julian_algorithm
     * @param integer $year
     * @return int[] month and day
     */
    private function getEristerMeeusJulian(int $year): array
    {
        $a = $year % 4;
        $b = $year % 7;
        $c = $year % 19;
        $d = (19 * $c + 15) % 30;
        $e = (2 * $a + 4 * $b - $d + 34) % 7;

        $month = intdiv($d + $e + 114, 31);
        $day = (($d + $e + 114) % 31) + 1;

        return [$month, $day];
    }
}
