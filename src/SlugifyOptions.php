<?php

declare(strict_types=1);

namespace SlugifyCli;

class SlugifyOptions
{
    protected ?string $separator;

    protected ?bool $lowercase;

    protected ?bool $trim;

    protected ?string $regexp;

    protected ?bool $lowercaseAfterRegexp;

    protected ?bool $stripTags;

    /** @var array<string>|null */
    protected ?array $rulesets;

    /** @param array<string>|null  $rulesets */
    public function __construct(
        ?string $separator,
        ?bool $lowercase,
        ?bool $trim,
        ?string $regexp,
        ?bool $lowercaseAfterRegexp,
        ?bool $stripTags,
        ?array $rulesets
    ) {
        $this->separator = $separator;
        $this->lowercase = $lowercase;
        $this->trim = $trim;
        $this->regexp = $regexp;
        $this->lowercaseAfterRegexp = $lowercaseAfterRegexp;
        $this->stripTags = $stripTags;
        $this->rulesets = $rulesets;
    }

    public function getSeparator(): ?string
    {
        return $this->separator;
    }

    public function getLowercase(): ?bool
    {
        return $this->lowercase;
    }

    public function getTrim(): ?bool
    {
        return $this->trim;
    }

    public function getRegexp(): ?string
    {
        return $this->regexp;
    }

    public function getLowercaseAfterRegexp(): ?bool
    {
        return $this->lowercaseAfterRegexp;
    }

    public function getStripTags(): ?bool
    {
        return $this->stripTags;
    }

    /** @return array<string>|null */
    public function getRulesets(): ?array
    {
        return $this->rulesets;
    }
}
