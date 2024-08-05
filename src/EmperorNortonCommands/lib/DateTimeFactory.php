<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

declare(strict_types=1);

namespace EmperorNortonCommands\lib;

use DateTimeImmutable;
use DateTimeZone;
use InvalidArgumentException;

/**
 * @package EmperorNortonCommands\lib\Ddate
 * @internal
 */
final class DateTimeFactory
{
    /**
     * @throws InvalidArgumentException if value is not a valid Gregorian date
     */
    public static function createFromStringOrNull(string|null $value): DateTimeImmutable
    {
        if ($value === null) {
            return new DateTimeImmutable();
        }
        if (!is_numeric($value) && 8 !== strlen($value)) {
            self::throwInvalidArgumentException($value, 'string is not numeric or given length is not 8 characters');
        }
        $day = (int)substr($value, 0, 2);
        $month = (int)substr($value, 2, 2);
        $year = (int)substr($value, 4, 4);
        if (!checkdate($month, $day, $year)) {
            self::throwInvalidArgumentException($value, 'is not a valid Gregorian date');
        }
        $result = DateTimeImmutable::createFromFormat(
                format: 'j-n-Y-H:i:s',
                datetime: sprintf('%1$d-%2$d-%3$d-00:00:00', $day, $month, $year),
                timezone: new DateTimeZone('UTC')
            );
        assert($result !== false);

        return $result;
    }

    /**
     * @throws InvalidArgumentException
     */
    private static function throwInvalidArgumentException(string $value, string $additionalInfo): void
    {
        throw new InvalidArgumentException(
            sprintf(
                'Given date value expected to be string (format dmY) or null, found "%1$s": %2$s',
                $value,
                $additionalInfo,
            )
        );
    }
}
