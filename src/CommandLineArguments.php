<?php

declare(strict_types=1);

namespace SlugifyCli;

class CommandLineArguments
{
    protected bool $showHelp;

    protected bool $showVersion;

    protected SlugifyOptions $slugifyOptions;

    /** @var array<string> */
    protected array $inputFilePaths;

    /**
     * @param array<string> $inputFilePaths
     */
    public function __construct(
        bool $showHelp,
        bool $showVersion,
        SlugifyOptions $slugifyOptions,
        array $inputFilePaths
    ) {
        $this->showHelp = $showHelp;
        $this->showVersion = $showVersion;
        $this->slugifyOptions = $slugifyOptions;
        $this->inputFilePaths = $inputFilePaths;
    }

    public function isShowHelp(): bool
    {
        return $this->showHelp;
    }

    public function isShowVersion(): bool
    {
        return $this->showVersion;
    }

    public function getSlugifyOptions(): SlugifyOptions
    {
        return $this->slugifyOptions;
    }

    /**
     * @return array<string>
     */
    public function getInputFilePaths(): array
    {
        return $this->inputFilePaths;
    }
}
