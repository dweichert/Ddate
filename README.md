# README

[![Build Status](https://travis-ci.org/dweichert/Ddate.svg?branch=master)](https://travis-ci.org/dweichert/Ddate)
[![Downloads this Month](https://img.shields.io/packagist/dm/ddate/ddate.svg?style=flat)](https://packagist.org/packages/ddate/ddate)
[![Latest stable](https://img.shields.io/packagist/v/ddate/ddate.svg?style=flat&label=stable)](https://packagist.org/packages/ddate/ddate)
[![Latest dev](https://img.shields.io/packagist/vpre/ddate/ddate.svg?style=flat&label=unstable)](https://packagist.org/packages/ddate/ddate)
[![License](https://img.shields.io/packagist/l/ddate/ddate.svg?style=flat&label=license)](https://packagist.org/packages/ddate/ddate)
[![Code Climate](https://codeclimate.com/github/dweichert/Ddate/badges/gpa.svg)](https://codeclimate.com/github/dweichert/Ddate)
[![SymfonyInsight](https://insight.symfony.com/projects/adbb07bc-edaa-4d6e-a113-0b766de6687a/mini.svg)](https://insight.symfony.com/projects/adbb07bc-edaa-4d6e-a113-0b766de6687a)
[![composer.lock](https://poser.pugx.org/ddate/ddate/composerlock)](https://packagist.org/packages/ddate/ddate)

## What is EmperorNortonCommands/lib/Ddate?

EmperorNortonCommands/lib/Ddate is an almost faithful recreation of the ddate
command formerly provided by the *util-linux* standard package of the Linux
operating system in PHP. It converts Gregorian to Discordian dates.

## Installation

The easiest way to use the library in your project is to install it by adding
it as a dependency to your project's composer.json file.

    $ composer require ddate/ddate "^1.1"
    
If you want to call the library via the command line or use it in a shell
script downloading the
[PHAR file](https://en.wikipedia.org/wiki/PHAR_(file_format)) from the
[releases page](https://github.com/dweichert/Ddate/releases/latest) can be
convenient. Everything is contained in a single file that can be called from
the command line and you can pass parameters as command line arguments (see
below for details). You can invoke *ddate* from the command line by executing
the file in the directory to which it was downloaded:
    
    $ ./ddate.phar

To show the help text and format string options use:

    $ ./ddate.phar --help

If you specify a locale as a second argument the format string options
(see below) for the given locale will be shown:

    $ ./ddate.phar --help de
    
The releases are signed using the GPG-key with the ID 
```A6FED5506250B129``` and the Fingerprint ```F1F7 A70A 51E2 D0FA 0903
65B7 A6FE D550 6250 B129```

You can verify the PHAR file using these commmands:

    $ gpg --keyserver pgp.mit.edu --recv-key 0xA6FED5506250B129
    $ gpg --fingerprint A6FED5506250B129
    $ gpg --verify ddate.phar.asc ddate.phar

## Usage

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

The method ddate() returns the date in Discordian date format. If called
with no parameters, the current system date will be used. 

### Parameters

If a format string is specified as the first parameter, the Discordian date
will be returned in a format specified by the string. This mechanism works
similarly to the format string mechanism of date(), only almost completely
differently.

To show the Discordian date for another day, a Gregorian date may be
specified as the second parameter of the function, in form of a day, month
and year (dmY), e.g. 29022012 (for the 29th of February 2012).

The third parameter is a string serving as a locale identifier. Currently
'en' (for English) and 'de' (for German) are supported by standard
formatters shipped with the library. English is the fallback should no
locale be specified.

#### Examples

    $ddate->ddate('Today is %{%A, the %e of %B%}, %Y.%N %nCelebrate %H', 18092013)

*Today is Sweetmorn, the 42nd of Bureaucracy, 3179.*

    $ddate->ddate("It's %{%A, the %e of %B%}, %Y. %N%nCelebrate %H", 26092013)

*It's Prickle-Prickle, the 50th of Bureaucracy, 3179.*

*Celebrate Bureflux*

    $ddate->ddate("Today's %{%A, the %e of %B%}, %Y. %N%nCelebrate %H", 29022016)

*Today's St. Tib's Day, 3182.*

    $ddate->ddate("Heute ist %{%A, %e %C%}, %Y. %N%nWir feiern %H", 29022020, "de")
    
*Heute ist St. Tibs Tag, 3186.*

    $ddate->ddate("%2%4Heute ist %{%A, %e %C%}, %Y. %N%nWir feiern %H", 09012018, "de")
    
*Heute ist Prickel-Prickel, 9. der Verwirrung, 3184.*

    $ddate->ddate("%2%4Heute ist %{%A, %e %C%}, %Y. %N%nWir feiern %H", 10012018, "de")

*Heute ist Orangewerdend, 10. der Verwirrung, 3184.* 

*Wir feiern Rückwärtstag (reformiert) und Binärtag*

### Method getSupportedFormatStringFields

    $ddate->getSupportedFormatStringFields()
    $ddate->getSupportedFormatStringFields("de")

Will return an array of all supported format string fields. The keys of
the returned array are the format fields supported in a format string and
the values provide an English description of the fields purpose.

The first optional parameter allows to specify a locale identifier, because
the format string fields are locale specific.

##  Releases

| Version | Supported PHP Versions                                                  | Remarks                                                                 |
|---------|-------------------------------------------------------------------------|-------------------------------------------------------------------------|
| 2.0.x   | 8.0.x                                                                   |                                                                         |
| 1.1.x   | 5.3.x, 5.4.x, 5.5.x, 5.6.x, 7.0.x, 7.1.x, 7.2.x, 7.3.x, 7.4.x           | Older PHP versions will be supported as long as the CI builds can be maintained.\* |
| 1.0.x   | --                                                                      | Version 1.0.x has reached its end of life and is  no longer supported.  |

\* Regrettably HHVM is no longer supported, as [HHVM no longer supports PHP](https://hhvm.com/blog/2018/09/12/end-of-php-support-future-of-hack.html) and [Composer no longer runs under HHVM](https://hhvm.com/blog/2019/02/11/hhvm-4.0.0.html), rendering the HHVM build permanently broken. Older versions of HHVM/Composer used to work, but your milage may vary.

## License

This program is in the public domain. Distribute freely. Or not.
See the file [LICENSE](LICENSE) for more details.


Hail Eris, All Hail Discordia,

Pope Rotund Deluxe

Bureflux, 3179

(last updated: Prickle-Prickle, 48th of The Aftermath, 3186)
