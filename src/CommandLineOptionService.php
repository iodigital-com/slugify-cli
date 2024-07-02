<?php

declare(strict_types=1);

namespace SlugifyCli;

use UnexpectedValueException;

use function array_key_exists;
use function count;
use function explode;
use function implode;
use function is_array;
use function sprintf;
use function strlen;
use function strval;
use function trim;

class CommandLineOptionService
{
    /**
     * @param array<string> $optionKeys
     */
    public function formatOptionKeys(array $optionKeys): string
    {
        $formattedOptions = [];
        foreach ($optionKeys as $optionKey) {
            $formattedOptions[] =
                sprintf('\'%s%s\'', ((strlen($optionKey) === 1) ? '-' : '--'), $optionKey);
        }
        return implode('/', $formattedOptions);
    }

    /**
     * @param array<string> $optionKeys
     * @param array<string, array<int, string|false>|string|false> $opts
     */
    public function hasOption(array $optionKeys, array $opts): bool
    {
        foreach ($optionKeys as $optionKey) {
            if (array_key_exists($optionKey, $opts)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param array<string> $optionKeys
     * @param array<string, array<int, string|false>|string|false> $opts
     */
    public function getOptionalStringOptionValue(array $optionKeys, array $opts): ?string
    {
        $optionValues = $this->getOptionValues($optionKeys, $opts);
        if (count($optionValues) > 1) {
            throw new UnexpectedValueException(sprintf(
                'multiple option values for option %s is not allowed',
                $this->formatOptionKeys($optionKeys)
            ));
        }
        foreach ($optionValues as $optionValue) {
            return $optionValue;
        }
        return null;
    }

    /**
     * @param array<string> $optionKeys
     * @param array<string, array<int, string|false>|string|false> $opts
     * @return array<string>
     */
    public function getOptionalArrayOptionValues(array $optionKeys, array $opts): ?array
    {
        $optionValuesString = $this->getOptionalStringOptionValue($optionKeys, $opts);
        if ($optionValuesString === null) {
            return null;
        }
        $optionValues = [];
        foreach (explode(',', $optionValuesString) as $optionValue) {
            if (trim($optionValue) === '') {
                continue;
            }
            $optionValues[] = trim($optionValue);
        }
        return $optionValues;
    }

    /**
     * @param array<string> $optionKeys
     * @param array<string, array<int, string|false>|string|false> $opts
     *
     * @return array<string>
     */
    public function getOptionValues(array $optionKeys, array $opts): array
    {
        $optionValues = [];
        foreach ($optionKeys as $optionKey) {
            if (!array_key_exists($optionKey, $opts)) {
                continue;
            }
            if (is_array($opts[$optionKey])) {
                foreach ($opts[$optionKey] as $optionValue) {
                    $optionValues[] = strval($optionValue);
                }
            } else {
                $optionValues[] = strval($opts[$optionKey]);
            }
        }
        return $optionValues;
    }
}
