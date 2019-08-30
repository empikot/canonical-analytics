<?php

namespace App\Infrastructure\LandingPage\Extractors;

use App\Domain\LandingPage\ValueObjects\StatsFilterCriteria;
use App\Infrastructure\LandingPage\Models\Stat;
use Illuminate\Support\Collection;

class LandingPageUrlsExtractor
{
    public function findAllRecentOnes(StatsFilterCriteria $filterCriteria): Collection
    {
        return Stat::where('created_at', '>=', $filterCriteria->getDateFrom())
            ->where('created_at', '<=', $filterCriteria->getDateTo())
            ->distinct('url')
            ->get()
            ->map(function ($value) {
                return $value->getAttributes()[0];
            });
    }
}
