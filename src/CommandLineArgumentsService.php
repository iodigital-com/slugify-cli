<?php

declare(strict_types=1);

namespace SlugifyCli;

use UnexpectedValueException;

use function array_key_exists;
use function array_slice;
use function basename;
use function getopt;
use function sprintf;

use const PHP_EOL;

class CommandLineArgumentsService
{
    public const DEFAULT_COMMAND_NAME = 'slugify-cli';

    protected const GETOPT_SHORT_OPTIONS = 'hvs:';

    protected const GETOPT_LONG_OPTIONS = [
        'help',
        'version',
        'separator:',
        'no-lowercase',
        'no-trim',
        'regexp:',
        'lowercase-after-regexp',
        'strip-tags',
        'rulesets:',
    ];

    private CommandLineOptionService $commandLineOptionService;

    /** @var array<int, string> */
    protected array $argv;

    /**
     * @param array<int, string> $argv
     */
    public function __construct(
        array $argv,
        CommandLineOptionService $commandLineOptionService
    ) {
        $this->commandLineOptionService = $commandLineOptionService;
        $this->argv = $argv;
    }

    public function getScriptName(): string
    {
        if (array_key_exists(0, $this->argv)) {
            return basename($this->argv[0]);
        }
        return self::DEFAULT_COMMAND_NAME;
    }

    public function getUsageHelp(): string
    {
        return $this->getSynopsis() . PHP_EOL . PHP_EOL . $this->getUsageDetails() . PHP_EOL;
    }

    protected function getSynopsis(): string
    {
        return sprintf('Usage: %s [OPTION...] [FILE ...]', $this->getScriptName());
    }

    protected function getUsageDetails(): string
    {
        return <<<'USAGE_DETAILS'
Transform each line from the given input FILEs to a slug and write it to STDOUT.
The transformation uses the cocur/slugify package to perform the transformation.

When no FILE is supplied, input is read from STDIN.

The following OPTIONs are available:
-h/--help: prints this help message
-v/--version: prints the version
-s/--separator: specify the separator to be used in the slugs (default '-')
--no-lowercase: do not convert slugs to lowercase
--no-trim: do not trim slugs
--regexp: specify the regular expression to replace characters with separator (default '/[^A-Za-z0-9]+/')
--lowercase-after-regexp: perform lowercasing after applying the regular expression
--strip-tags: strip HTML tags
--rulesets: specify a comma-separated list of rulesets to use and in which order
            (see https://github.com/cocur/slugify#rulesets for details)
USAGE_DETAILS;
    }

    /**
     * @throws UnexpectedValueException
     */
    public function parseOptions(): CommandLineArguments
    {
        $restIndex = 0;
        /** @var array<string, array<int, string|false>|string|false>|false $opts */
        $opts = getopt(self::GETOPT_SHORT_OPTIONS, self::GETOPT_LONG_OPTIONS, $restIndex);
        if ($opts === false) {
            throw new UnexpectedValueException('could not parse command-line options');
        }
        return new CommandLineArguments(
            $this->commandLineOptionService->hasOption(['h', 'help'], $opts),
            $this->commandLineOptionService->hasOption(['v', 'version'], $opts),
            new SlugifyOptions(
                $this->commandLineOptionService->getOptionalStringOptionValue(['s', 'separator'], $opts),
                !$this->commandLineOptionService->hasOption(['no-lowercase'], $opts),
                !$this->commandLineOptionService->hasOption(['no-trim'], $opts),
                $this->commandLineOptionService->getOptionalStringOptionValue(['regexp'], $opts),
                $this->commandLineOptionService->hasOption(['lowercase-after-regexp'], $opts),
                $this->commandLineOptionService->hasOption(['strip-tags'], $opts),
                $this->commandLineOptionService->getOptionalArrayOptionValues(['rulesets'], $opts),
            ),
            array_slice($this->argv, $restIndex)
        );
    }
}
