<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */
declare(strict_types=1);

namespace EmperorNortonCommands\lib;

/**
 * @package EmperorNortonCommands\lib\Ddate
 * @api
 */
interface FormatterInterface
{
    public function format(string|null $format, DiscordianDate $ddate): string;

    /**
     * Get supported format string fields.
     *
     * @return array<string, string>
     */
    public function getSupportedFormatStringFields(): array;
}
