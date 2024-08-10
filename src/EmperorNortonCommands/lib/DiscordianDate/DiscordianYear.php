<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */
declare(strict_types=1);

namespace EmperorNortonCommands\lib\DiscordianDate;

/**
 * @package EmperorNortonCommands\lib\Ddate
 * @api
 */
final readonly class DiscordianYear
{
    public function __construct(public int $value)
    {
    }
}
