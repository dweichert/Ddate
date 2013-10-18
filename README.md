#README

##What is EmperorNortonCommands/lib/Ddate?

EmperorNortonCommands/Ddate is an almost faithful recreation of the ddate
command provided by the *util-linux* standard package of the Linux operating
system in PHP. It converts Gregorian to Discordian dates.

##Public properties

None.

##Public methods

###ddate

    string ddate ( [string $format, int $date] )

Returns the date in Discordian date format. If called with no arguments,
the current system date will be used. Alternatively, a Gregorian date may
be specified as the second argument of the function, in form of a day,
month and year (dmY), e.g. 29022012 (for the 29th of February 2012).

If a format string is specified as the first argument, the Discordian date
will be returned in a format specified by the string. This mechanism works
similarly to the format string mechanism of date(), only almost completely
differently.

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