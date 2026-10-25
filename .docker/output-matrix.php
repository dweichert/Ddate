<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

/*
 * Prints the output of the public API for a large set of inputs, one JSON
 * line per case, including exceptions and PHP errors. Used by
 * compare-with-1.1.sh to compare the current code with a 1.1 release.
 *
 * Usage: php output-matrix.php PROJECT_DIR
 *
 * Must only use the API of 1.1, so it runs against both code bases.
 */

declare(strict_types=1);

use EmperorNortonCommands\lib\Ddate;

require $argv[1] . '/vendor/autoload.php';

$errors = [];
set_error_handler(static function (int $severity, string $message) use (&$errors): bool {
    $errors[] = $severity . ': ' . $message;
    return true;
});

function record(string $case, callable $call): void
{
    global $errors;
    $errors = [];
    try {
        $result = $call();
    } catch (Throwable $e) {
        $result = get_class($e) . ': ' . $e->getMessage();
    }
    echo json_encode([$case, $result, $errors], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), "\n";
}

$dates = [];
foreach ([2012, 2013] as $year) {
    $day = new DateTimeImmutable("$year-01-01");
    while ((int)$day->format('Y') === $year) {
        $dates[] = $day->format('dmY');
        $day = $day->modify('+1 day');
    }
}
// Erister before and after 1753, centuries, X-Days, far future
foreach (['27030354', '31031700', '01041752', '22041753', '28021900', '01031900', '29022000',
          '01032100', '04071998', '05071998', '06071998', '04078661', '05078661', '06078661',
          '23039000', '01010001', '31129999', '02052014', '29082014', '31102014'] as $date) {
    $dates[] = $date;
}

$formats = [
    'en' => '%{%A|%a|%B|%b|%d|%e%}|%Y|%X|%x|%t|%n|%%|%z',
    'de' => '%{%A|%a|%B|%b|%C|%d|%e|%E%}|%Y|%X|%x|%t|%n|%%|%z',
];
$holydayFormats = ['%H', '%1%H', '%2%H', '%3%H', '%4%H', '%1%3%H', '%2%3%4%H', 'x%Ny %H'];

$ddate = new Ddate();
foreach ($dates as $date) {
    foreach (['en', 'de'] as $locale) {
        record("ddate(null, $date, $locale)", fn () => $ddate->ddate(null, $date, $locale));
        record("ddate(all, $date, $locale)", fn () => $ddate->ddate($formats[$locale], $date, $locale));
        foreach ($holydayFormats as $format) {
            record("ddate($format, $date, $locale)", fn () => $ddate->ddate($format, $date, $locale));
        }
    }
}

$stringable = new class {
    public function __toString(): string
    {
        return '%A %e %B';
    }
};
$localeStringable = new class {
    public function __toString(): string
    {
        return 'DE';
    }
};
$odd = [
    'null' => null, 'empty string' => '', 'int' => 123, 'float' => 1.5, 'true' => true, 'false' => false,
    'array' => ['%A'], 'stdClass' => new stdClass(), 'stringable' => $stringable,
];

foreach ($odd as $name => $format) {
    record("ddate(format $name)", fn () => $ddate->ddate($format, '05012014'));
}
foreach ($odd + ['locale stringable' => $localeStringable, 'en' => 'en', 'EN' => 'EN', 'de' => 'de',
                 'De' => 'De', 'deu' => 'deu', 'de_DE' => 'de_DE', 'fr' => 'fr', 'zz' => 'zz'] as $name => $locale) {
    record("ddate(locale $name)", fn () => $ddate->ddate('%A, %e %B %Y %H', '05012014', $locale));
    record("getSupportedFormatStringFields($name)", fn () => $ddate->getSupportedFormatStringFields($locale));
}
foreach (['string 29021997' => '29021997', 'int 29021997' => 29021997, 'int 5012014' => 5012014,
          'int 12122012' => 12122012, 'float' => 12122012.0, 'abc' => 'abc', 'abcdefgh' => 'abcdefgh',
          '7 digits' => '1234567', '9 digits' => '123456789', '31042013' => '31042013',
          '00000000' => '00000000', '29022100' => '29022100', 'with spaces' => ' 1012013',
          'true' => true, 'false' => false, 'array' => ['01012013'], 'stdClass' => new stdClass()] as $name => $date) {
    record("ddate(date $name)", fn () => $ddate->ddate(null, $date));
}
record('ddate() default format matches today', fn () => $ddate->ddate() === $ddate->ddate(null, date('dmY')));
record('new Ddate(stdClass)', fn () => new Ddate(new stdClass()));
record('new Ddate(null, en)', fn () => new Ddate(null, 'en'));
