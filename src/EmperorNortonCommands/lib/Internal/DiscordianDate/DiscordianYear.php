<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */
declare(strict_types=1);

namespace EmperorNortonCommands\lib\Internal\DiscordianDate;

/**
 * @package EmperorNortonCommands\lib\Ddate
 * @internal
 */
final readonly class DiscordianYear
{
    public function __construct(public int $value)
    {
    }
}
