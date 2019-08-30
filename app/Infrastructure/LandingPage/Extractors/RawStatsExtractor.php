<?php

namespace App\Infrastructure\LandingPage\Extractors;

use App\Domain\LandingPage\ValueObjects\StatsFilterCriteria;
use App\Infrastructure\LandingPage\Models\Stat;

class RawStatsExtractor
{
    /**
     * @param StatsFilterCriteria $filterCriteria
     * @return mixed
     */
    public function getSumOfOffersQuantitiesByUrlAndCreationDateRange(StatsFilterCriteria $filterCriteria)
    {
        return Stat::where('url', '=', $filterCriteria->getUrl() ?? '')
            ->whereBetween('created_at', [$filterCriteria->getDateFrom(), $filterCriteria->getDateTo()])
            ->get();
    }
}
