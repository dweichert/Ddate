#README

[![Build Status](https://travis-ci.org/dweichert/Ddate.svg?branch=master)](https://travis-ci.org/dweichert/Ddate)
[![Downloads this Month](https://img.shields.io/packagist/dm/ddate/ddate.svg?style=flat-square)](https://packagist.org/packages/ddate/ddate)
[![Latest stable](https://img.shields.io/packagist/v/ddate/ddate.svg?style=flat-square&label=stable)](https://packagist.org/packages/ddate/ddate)
[![Latest dev](https://img.shields.io/packagist/vpre/ddate/ddate.svg?style=flat&label=unstable)](https://packagist.org/packages/ddate/ddate)
[![License](https://img.shields.io/packagist/l/ddate/ddate.svg?style=flat&label=license)](https://packagist.org/packages/ddate/ddate)
[![Code Climate](https://codeclimate.com/github/dweichert/Ddate/badges/gpa.svg)](https://codeclimate.com/github/dweichert/Ddate)
[![Code Climate](https://img.shields.io/codeclimate/coverage/github/dweichert/Ddate.svg)](https://codeclimate.com/github/dweichert/Ddate)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/adbb07bc-edaa-4d6e-a113-0b766de6687a.svg?style=flat&label=insight)](https://insight.sensiolabs.com/projects/adbb07bc-edaa-4d6e-a113-0b766de6687a)

##What is EmperorNortonCommands/lib/Ddate?

EmperorNortonCommands/lib/Ddate is an almost faithful recreation of the ddate
command provided by the *util-linux* standard package of the Linux operating
system in PHP. It converts Gregorian to Discordian dates.

##Installation

The easiest way to install the library is adding it as a dependency to your
project's composer.json file.

    $ composer require ddate/ddate "~1.0"

##Usage

```
// use the class Ddate

use EmperorNortonCommands\lib\Ddate;

class Foo
{
    public function foo()
    {
        // set $date to the current Discordian date using the default format
        $ddate = new Ddate();
        $date = $ddate->ddate();

        // for more formatting options and setting custom dates see below
    }
}
```
###ddate

    string ddate ( [string $format, int $date, string $locale] )

Returns the date in Discordian date format. If called with no arguments,
the current system date will be used. Alternatively, a Gregorian date may
be specified as the second argument of the function, in form of a day,
month and year (dmY), e.g. 29022012 (for the 29th of February 2012).

If a format string is specified as the first argument, the Discordian date
will be returned in a format specified by the string. This mechanism works
similarly to the format string mechanism of date(), only almost completely
differently.

The third argument is a string serving as a locale identifier. Currently
'en' (for English) and 'de' (for German) are supported by standard
formatters shipped with the library. English is the fallback should no
locale be specified.

#### Examples

    ddate('Today is %{%A, the %e of %B%}, %Y.%N %nCelebrate %H', 18092013)

Today is Sweetmorn, the 42nd of Bureaucracy, 3179.

    ddate("It's %{%A, the %e of %B%}, %Y. %N%nCelebrate %H", 26092013)

It's Prickle-Prickle, the 50th of Bureaucracy, 3179.

Celebrate Bureflux

    ddate("Today's %{%A, the %e of %B%}, %Y. %N%nCelebrate %H", 29022016)

Today's St. Tib's Day, 3182.

Celebrate St. Tib's Day

###getAvailableFormatStringFields

    array getAvailableFormatStringFields (void)

Will return an array of all supported format string fields. The keys of
the returned array are the format fields supported in a format string and
the values provide an English description of the fields purpose.

##License

This program is in the public domain. Distribute freely. Or not.
See the file LICENSE for more details.


Hail Eris, All Hail Discordia,

Pope Rotund Deluxe

Bureflux, 3179
