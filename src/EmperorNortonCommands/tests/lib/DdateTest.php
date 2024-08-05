<?php
/**
 * This file is part of the Emperor Norton Commands.
 *
 * Public domain. All rites reversed.
 */

namespace EmperorNortonCommands\tests\lib;

use DateTime;
use EmperorNortonCommands\lib\Ddate;
use EmperorNortonCommands\lib\locale\en\StandardFormatter as EnglishStandardFormatter;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleXMLElement;

final class DdateTest extends TestCase
{
    #[DataProvider('ddateDataProvider')]
    public function testDdate(
        string|int $gregorian,
        string $discordian,
        object|string|null $format,
    ): void {
        $actual = (new Ddate())->ddate($format, $gregorian);
        self::assertEquals($discordian, $actual);
    }

    public static function ddateDataProvider(): array
    {
        return [
            ['03051998', 'Pungenday, Discord 50, 3164 YOLD', null],
            ['29021996', 'St. Tib\'s Day 3162 YOLD', null],
            ['07021974', 'Pungenday, Chaos 38, 3140 YOLD', null],
            ['31011973', 'Sweetmorn, Chaos 31, 3139 YOLD', null],
            ['16022008', 'Boomtime, Chaos 47, 3174 YOLD', null],
            ['24011948', 'Prickle-Prickle, Chaos 24, 3114 YOLD', null],
            ['25091944', 'Pungenday, Bureaucracy 49, 3110 YOLD', null],
            ['04091920', 'Boomtime, Bureaucracy 28, 3086 YOLD', null],
            ['20091928', 'Pungenday, Bureaucracy 44, 3094 YOLD', null],
            ['30011901', 'Setting Orange, Chaos 30, 3067 YOLD', null],
            ['29022012', 'St. Tib\'s Day 3178 YOLD', null],
            ['01032012', 'Setting Orange, Chaos 60, 3178 YOLD', null],
            ['17091859', 'Setting Orange, Bureaucracy 41, 3025 YOLD', null],
            ['12122012', 'Sweetmorn, The Aftermath 54, 3178 YOLD', null],
            ['07072007', 'Pungenday, Confusion 42, 3173 YOLD', null],
            ['29022012', "Today's St. Tib's Day of FNORD, 3178 YOLD", "Today's %{%A, the %e%} of %B, %Y YOLD"],
            ['29022012', "Today's St. Tib's Day FNORD (FNORD), the FNORD (FNORD) of FNORD (FNORD), 3178 YOLD", "Today's %{%} %A (%a), the %e (%d) of %B (%b), %Y YOLD"],
            ['01012013', 'SM, Chaos 1, 3179', '%{%a, %B %d,%} %Y'],
            ['02012013', 'BT, Chaos 2, 3179', '%{%a, %B %d,%} %Y'],
            ['03012013', 'PD, Chaos 3, 3179', '%{%a, %B %d,%} %Y'],
            ['04012013', 'PP, Chaos 4, 3179', '%{%a, %B %d,%} %Y'],
            ['05012013', 'SO, Chaos 5, 3179', '%{%a, %B %d,%} %Y'],
            ['05012014', 'Today is Setting Orange, the 5th of Chaos, 3180 YOLD' . "\n\t" . 'We celebrate Mungday.', 'Today is %{%A, the %e of %B, %Y YOLD%N%n%tWe celebrate %H.'],
            ['06012014', 'Today is Sweetmorn, the 6th of Chaos, 3180 YOLD', 'Today is %{%A, the %e of %B, %Y YOLD%N%n%tWe celebrate %H.'],
            ['19022014', 'Today is Setting Orange, the 50th of Chaos, 3180 YOLD' . "\n\t" . 'We celebrate Chaoflux.', 'Today is %{%A, the %e of %B, %Y YOLD%N%n%tWe celebrate %H.'],
            ['19032014', 'Today is Pungenday, the 5th of Discord, 3180 YOLD' . "\n\t" . 'We celebrate Mojoday.', 'Today is %{%A, the %e of %B, %Y YOLD%N%n%tWe celebrate %H.'],
            ['03052014', 'Today is Pungenday, the 50th of Discord, 3180 YOLD' . "\n\t" . 'We celebrate Discoflux.', 'Today is %{%A, the %e of %B, %Y YOLD%N%n%tWe celebrate %H.'],
            ['31052014', 'Today is Sweetmorn, the 5th of Confusion, 3180 YOLD' . "\n\t" . 'We celebrate Syaday.', 'Today is %{%A, the %e of %B, %Y YOLD%N%n%tWe celebrate %H.'],
            ['15072014', 'Today is Sweetmorn, the 50th of Confusion, 3180 YOLD' . "\n\t" . 'We celebrate Conflux.', 'Today is %{%A, the %e of %B, %Y YOLD%N%n%tWe celebrate %H.'],
            ['12082014', 'Today is Prickle-Prickle, the 5th of Bureaucracy, 3180 YOLD' . "\n\t" . 'We celebrate Zaraday.', 'Today is %{%A, the %e of %B, %Y YOLD%N%n%tWe celebrate %H.'],
            ['26092014', 'Today is Prickle-Prickle, the 50th of Bureaucracy, 3180 YOLD' . "\n\t" . 'We celebrate Bureflux.', 'Today is %{%A, the %e of %B, %Y YOLD%N%n%tWe celebrate %H.'],
            ['24102014', 'Today is Boomtime, the 5th of The Aftermath, 3180 YOLD' . "\n\t" . 'We celebrate Maladay.', 'Today is %{%A, the %e of %B, %Y YOLD%N%n%tWe celebrate %H.'],
            ['08122014', 'Today is Boomtime, the 50th of The Aftermath, 3180 YOLD' . "\n\t" . 'We celebrate Afflux.', 'Today is %{%A, the %e of %B, %Y YOLD%N%n%tWe celebrate %H.'],
            ['19101999', 'Boomtime, Bureaucracy 73, 3165 YOLD', null],
            ['13032000', 'Boomtime, Chaos 72, 3166 YOLD', null],
            ['14032000', 'Pungenday, Chaos 73, 3166 YOLD', null],
            ['15032000', 'Prickle-Prickle, Discord 1, 3166 YOLD', null],
            ['30121999', 'Prickle-Prickle, The Aftermath 72, 3165 YOLD', new \stdClass()],
            ['31121999', 'Setting Orange, The Aftermath 73, 3165 YOLD', new \stdClass()],
            ['01012000', '', new SimpleXMLElement('<xml/>')],
            ['14031999', 'PD, Chs 73rd 3165', '%a, %b %e %Y'],
            ['15031999', 'PP, Dsc 1st 3165', '%a, %b %e %Y'],
            ['26051999', 'SM, Dsc 73rd 3165', '%a, %b %e %Y'],
            ['27051999', 'BT, Cfn 1st 3165', '%a, %b %e %Y'],
            ['07081999', 'PP, Cfn 73rd 3165', '%a, %b %e %Y'],
            ['08081999', 'SO, Bcy 1st 3165', '%a, %b %e %Y'],
            ['19101999', 'BT, Bcy 73rd 3165', '%a, %b %e %Y'],
            ['20101999', 'PD, Afm 1st 3165', '%a, %b %e %Y'],
            ['31011999', 'SM, Chs 31st 3165', '%a, %b %e %Y'],
            ['01012000', 'SM, Chs 1st 3166', '%a, %b %e %Y'],
            ['02012000', 'BT, Chs 2nd 3166', '%a, %b %e %Y'],
            ['03012000', 'PD, Chs 3rd 3166', '%a, %b %e %Y'],
            ['04012000', 'PP, Chs 4th 3166', '%a, %b %e %Y'],
            ['05012000', 'SO, Chs 5th 3166', '%a, %b %e %Y'],
            ['10012000', 'SO, Chs 10th 3166', '%a, %b %e %Y'],
            ['11012000', 'SM, Chs 11th 3166', '%a, %b %e %Y'],
            ['12012000', 'BT, Chs 12th 3166', '%a, %b %e %Y'],
            ['13012000', 'PD, Chs 13th 3166', '%a, %b %e %Y'],
            ['14012000', 'PP, Chs 14th 3166', '%a, %b %e %Y'],
            ['14032000', 'PD, Chs 73rd 3166', '%a, %b %e %Y'],
            ['15032000', 'PP, Dsc 1st 3166', '%a, %b %e %Y'],
            ['26052000', 'SM, Dsc 73rd 3166', '%a, %b %e %Y'],
            ['27052000', 'BT, Cfn 1st 3166', '%a, %b %e %Y'],
            ['07082000', 'PP, Cfn 73rd 3166', '%a, %b %e %Y'],
            ['08082000', 'SO, Bcy 1st 3166', '%a, %b %e %Y'],
            ['19102000', 'BT, Bcy 73rd 3166', '%a, %b %e %Y'],
            ['20102000', 'PD, Afm 1st 3166', '%a, %b %e %Y'],
            ['31012000', 'SM, Chs 31st 3166', '%a, %b %e %Y'],
            ['01012001', 'SM, Chs 1st 3167', '%a, %b %e %Y'],
            ['29022012', "Today's St. Tib's Day 3178 YOLD 2428624 days 'til X-Day", "Today's %{%A, the %e of %B,%} %Y YOLD %X days 'til X-Day"],
            [18092013, 'Today is Sweetmorn, the 42nd of Bureaucracy, 3179.', 'Today is %{%A, the %e of %B%}, %Y.%N %nCelebrate %H'],
            [26092013, "It's Prickle-Prickle, the 50th of Bureaucracy, 3179. \nCelebrate Bureflux", "It's %{%A, the %e of %B%}, %Y. %N%nCelebrate %H"],
            [29022016, "Today's St. Tib's Day, 3182.", "Today's %{%A, the %e of %B%}, %Y.%N Celebrate %H"],
            // Although X-Day happens on 5th of July, it takes place 11 am UTC/
            // 7 am EDT. Hence there is still one day (37 hours) diff and not
            // two days (48 hours) for July 7th 12am UTC).
            ['05071998', "0 days 'til X-Day", "%x days 'til X-Day"],
            ['07021974', "8914 days 'til X-Day", "%x days 'til X-Day"],
            ['07071998', "-1 days 'til X-Day", "%x days 'til X-Day"],
            ['27062016', "-6566 days 'til X-Day", "%x days 'til X-Day"],
            ['05078661', "0 days 'til X-Day", "%X days 'til X-Day"],
            ['07078661', "-1 days 'til X-Day", "%X days 'til X-Day"]
        ];
    }

    /**
     * Test ddate() with invalid argument (string too long and not numeric).
     */
    public function testInvalidDateWrongType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Given date value expected to be string (format dmY) or null, found "Lorem ipsum dolor sit amet.": string is not numeric or given length is not 8 characters');

        (new Ddate())->ddate(null, 'Lorem ipsum dolor sit amet.');
    }

    /**
     * Test ddate() with invalid argument (not a valid Gregorian date).
     */
    public function testInvalidDate(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Given date value expected to be string (format dmY) or null, found "29021997": is not a valid Gregorian date');

        (new Ddate())->ddate(null, 29021997);
    }

    /**
     * Test ddate with no arguments.
     *
     * Test default behaviour uses expected format and is the same as if
     * today's day month year were given.
     */
    public function testDdateNoArgs(): void
    {
        $date = new DateTime();
        $actual = (new Ddate())->ddate();
        $expected = (new Ddate())->ddate('%{%A, %B %d,%} %Y YOLD', $date->format('dmY'));
        self::assertEquals($expected, $actual);
    }

    /**
     * Test getSupportedFormatFields().
     */
    public function testGetSupportedFormatFields(): void
    {
        $formatter = new EnglishStandardFormatter();
        $expected = $formatter->getSupportedFormatStringFields();
        $actual = (new Ddate())->getSupportedFormatStringFields();
        self::assertEquals($expected, $actual);
    }

    /**
     * Test getSupportedFormatFields('en').
     */
    public function testGetSupportedFormatFieldsLocaleEn(): void
    {
        $formatter = new EnglishStandardFormatter();
        $expected = $formatter->getSupportedFormatStringFields();
        $actual = (new Ddate())->getSupportedFormatStringFields('en');
        self::assertEquals($expected, $actual);
    }
}
