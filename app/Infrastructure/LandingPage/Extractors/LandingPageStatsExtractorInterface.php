<?php

namespace App\Infrastructure\LandingPage\Extractors;

use App\Domain\LandingPage\ValueObjects\StatsFilterCriteria;

interface LandingPageStatsExtractorInterface
{
    public function getStats(StatsFilterCriteria $filterCriteria): array;
}
