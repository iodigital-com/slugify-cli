<?php

declare(strict_types=1);

namespace SlugifyCli;

use Cocur\Slugify\SlugifyInterface;
use Throwable;
use UnexpectedValueException;

use function fclose;
use function fgets;
use function fopen;
use function fwrite;
use function is_file;
use function is_readable;
use function rtrim;
use function sprintf;
use function str_replace;
use function strpos;

use const PHP_EOL;
use const STDERR;
use const STDIN;
use const STDOUT;

class Application
{
    protected CommandLineArgumentsService $commandLineOptionsService;

    protected VersionHelper $versionHelper;

    protected SlugifyFactory $slugifyFactory;

    /**
     * @param array<int, string> $argv
     */
    public function __construct(array $argv)
    {
        $this->commandLineOptionsService = new CommandLineArgumentsService($argv, new CommandLineOptionService());
        $this->versionHelper = new VersionHelper();
        $this->slugifyFactory = new SlugifyFactory();
    }

    public function run(): int
    {
        try {
            $commandLineOptions = $this->commandLineOptionsService->parseOptions();
            if ($commandLineOptions->isShowHelp()) {
                fwrite(STDERR, $this->commandLineOptionsService->getUsageHelp());
                return 0;
            }
            if ($commandLineOptions->isShowVersion()) {
                fwrite(STDERR, sprintf(
                    '%s version %s',
                    $this->commandLineOptionsService->getScriptName(),
                    $this->versionHelper->getVersion()
                ) . PHP_EOL);
                return 0;
            }
            $this->slugifyFiles(
                $this->slugifyFactory->create($commandLineOptions->getSlugifyOptions()),
                $commandLineOptions->getInputFilePaths()
            );
            return 0;
        } catch (Throwable $throwable) {
            fwrite(STDERR, sprintf('error: %s', $throwable->getMessage()) . PHP_EOL);
            return 1;
        }
    }

    /**
     * @param array<string> $inputFilePaths
     */
    protected function slugifyFiles(SlugifyInterface $slugify, array $inputFilePaths): void
    {
        if ($inputFilePaths === []) {
            $this->slugifyStream($slugify, STDIN, STDOUT);
        } else {
            foreach ($inputFilePaths as $inputFilePath) {
                $inputStream = $this->getInputStream($inputFilePath);
                $this->slugifyStream($slugify, $inputStream, STDOUT);
                fclose($inputStream);
            }
        }
    }

    /**
     * @return resource
     */
    protected function getInputStream(string $filePath)
    {
        if (strpos($filePath, '/dev/') === 0) {
            $inputStream = fopen(str_replace('/dev/', 'php://', $filePath), 'rb');
        } else {
            if (!is_file($filePath)) {
                throw new UnexpectedValueException(sprintf('file \'%s\' does not exist', $filePath));
            }
            if (!is_readable($filePath)) {
                throw new UnexpectedValueException(sprintf('file \'%s\' is not readable', $filePath));
            }
            $inputStream = fopen($filePath, 'rb');
        }
        if ($inputStream === false) {
            throw new UnexpectedValueException(sprintf('could not open \'%s\' for reading', $filePath));
        }
        return $inputStream;
    }

    /**
     * @param resource $inputStream
     * @param resource $outputStream
     */
    protected function slugifyStream(SlugifyInterface $slugify, $inputStream, $outputStream): void
    {
        $labelsLine = fgets($inputStream);
        while ($labelsLine !== false) {
            $label = rtrim($labelsLine);
            $result = @fwrite($outputStream, $this->slugifyValue($slugify, $label) . PHP_EOL);
            if ($result === false) {
                return;
            }
            $labelsLine = fgets($inputStream);
        }
    }

    protected function slugifyValue(SlugifyInterface $slugify, string $value): string
    {
        return $slugify->slugify($value);
    }
}
