Slugify CLI
===========

A CLI wrapper around the [cocur/slugify](https://github.com/cocur/slugify) package.
All options that can be supplied via the Slugify constructor are available as command-line options.  

Installation
------------

Add the package as a requirement to your project:

    $ composer require iodigital-com/slugify-cli

This will install the `slugify-cli` script to the `vendor/bin` folder of the project.

Or as a global requirement:

    $ composer global require iodigital-com/slugify-cli

This will install the `slugify` script to the `$HOME/.composer/vendor/bin` folder.

Usage
-----

Usage: `slugify [OPTION...] [FILE ...]`

Transform each line from the given input `FILE`s to a slug and write it to `STDOUT`.
The transformation uses the `cocur/slugify` package to perform the transformation.

When no `FILE` is supplied, input is read from `STDIN`.

The following `OPTION`s are available:
 
* `-h`/`--help`: prints usage information
* `-v`/`--version`: prints the version
* `-s`/`--separator`: specify the separator to be used in the slugs (default `-`)
* `--no-lowercase`: do not convert slugs to lowercase
* `--no-trim`: do not trim slugs
* `--regexp`: specify the regular expression to replace characters with separator (default `/[^A-Za-z0-9]+/`)
* `--lowercase-after-regexp`: perform lowercasing after applying the regular expression
* `--strip-tags`: strip HTML tags
* `--rulesets`: specify a comma-separated list of rulesets to use and in which order
(see https://github.com/cocur/slugify#rulesets for details)

Examples
--------

Basic usage without any options:

    $ echo 'Déjà Vu!' | bin/slugify
    deja-vu

    $ echo 'Fußgängerübergangsmörtel' | bin/slugify
    fussgaengeruebergangsmoertel

Using a different separator:

    $ echo 'Déjà Vu!' | bin/slugify -s _
    deja_vu

Do not use lowercasing or trimming:

    $ echo 'Déjà Vu!' | bin/slugify --no-lowercase --no-trim
    Deja-Vu-

Use a different regex and lowercase after regex:

    $ echo 'Déjà Vu!' | bin/slugify --regexp '/[^A-Z]+/' --lowercase-after-regexp
    d-v

Strip tags:

    $ echo '<p>Déjà <strong>Vu!</strong></p>' | bin/slugify --strip-tags
    deja-vu

Use a different ruleset:

    $ echo 'Gülümsemek' | bin/slugify --rulesets default,turkish
    gulumsemek

Read multiple files:

    $ bin/slugify <(echo 'Hello world!') <(echo 'foo bar baz')
    hello-world
    foo-bar-baz
