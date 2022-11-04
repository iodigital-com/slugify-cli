<?php

declare(strict_types=1);

namespace SlugifyCli;

use Cocur\Slugify\Slugify;
use Cocur\Slugify\SlugifyInterface;

class SlugifyFactory
{
    public function create(SlugifyOptions $slugifyOptions): SlugifyInterface
    {
        $slugifyOptionsArray = [];
        if ($slugifyOptions->getSeparator() !== null) {
            $slugifyOptionsArray['separator'] = $slugifyOptions->getSeparator();
        }
        if ($slugifyOptions->getLowercase() !== null) {
            $slugifyOptionsArray['lowercase'] = $slugifyOptions->getLowercase();
        }
        if ($slugifyOptions->getTrim() !== null) {
            $slugifyOptionsArray['trim'] = $slugifyOptions->getTrim();
        }
        if ($slugifyOptions->getRegexp() !== null) {
            $slugifyOptionsArray['regexp'] = $slugifyOptions->getRegexp();
        }
        if ($slugifyOptions->getLowercaseAfterRegexp() !== null) {
            $slugifyOptionsArray['lowercase_after_regexp'] = $slugifyOptions->getLowercaseAfterRegexp();
        }
        if ($slugifyOptions->getStripTags() !== null) {
            $slugifyOptionsArray['strip_tags'] = $slugifyOptions->getStripTags();
        }
        if ($slugifyOptions->getRulesets() !== null) {
            $slugifyOptionsArray['rulesets'] = $slugifyOptions->getRulesets();
        }
        return new Slugify($slugifyOptionsArray);
    }
}
